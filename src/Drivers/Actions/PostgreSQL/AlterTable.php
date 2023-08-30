<?php

namespace ModernPDO\Drivers\Actions\PostgreSQL;

use ModernPDO\Actions\AlterTable as BaseAlterTable;

/**
 * Class for updating tables.
 */
class AlterTable extends BaseAlterTable
{
    /**
     * Updates table.
     */
    public function execute(): bool
    {
        // !!! ATTENSION !!!
        // PostgreSQL does not support queries with multiple rename
        // So we will run them separately in transaction

        if (
            $this->newName === ''
            && empty($this->addFields)
            && empty($this->renameFields)
            && empty($this->dropFields)
        ) {
            return false;
        }

        // Store values
        $newName = $this->newName;
        $addFields = $this->addFields;
        $renameFields = $this->renameFields;
        $dropFields = $this->dropFields;

        // Clear action values
        $this->newName = '';
        $this->addFields = [];
        $this->renameFields = [];
        $this->dropFields = [];

        // Execute

        $transaction = $this->mpdo->transaction();

        $transaction->begin();

        // Rename fields

        foreach ($renameFields as $key => $value) {
            $this->renameFields = [$key => $value];

            if (!parent::execute()) {
                $transaction->rollBack();

                return false;
            }
        }

        $this->renameFields = [];

        $this->newName = $newName;
        $this->addFields = $addFields;
        $this->dropFields = $dropFields;

        if (!parent::execute() && !empty($renameFields)) {
            $transaction->rollBack();

            return false;
        }

        $transaction->commit();

        // Restore action values
        $this->newName = $newName;
        $this->addFields = $addFields;
        $this->renameFields = $renameFields;
        $this->dropFields = $dropFields;

        return true;
    }
}
