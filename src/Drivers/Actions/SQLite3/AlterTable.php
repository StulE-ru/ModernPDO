<?php

namespace ModernPDO\Drivers\Actions\SQLite3;

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
        // SQLite does not support queries with multiple parameters
        // So we will run them separately in transaction

        if (
            $this->newName === '' &&
            empty($this->addFields) &&
            empty($this->renameFields) &&
            empty ($this->dropFields)
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

        // Rename table

        $this->newName = $newName;

        if (!parent::execute() && $this->newName !== '') {
            $transaction->rollBack();

            return false;
        }

        $this->newName = '';

        // Add fields

        foreach ($addFields as $value) {
            $this->addFields = [$value];

            if (!parent::execute()) {
                $transaction->rollBack();

                return false;
            }
        }

        $this->addFields = [];

        // Rename fields

        foreach ($renameFields as $key => $value) {
            $this->renameFields = [$key => $value];

            if (!parent::execute()) {
                $transaction->rollBack();

                return false;
            }
        }

        $this->renameFields = [];

        // Drop fields

        foreach ($dropFields as $value) {
            $this->dropFields = [$value];

            if (!parent::execute()) {
                $transaction->rollBack();

                return false;
            }
        }

        $this->dropFields = [];

        $transaction->commit();

        // Restore action values
        $this->newName = $newName;
        $this->addFields = $addFields;
        $this->renameFields = $renameFields;
        $this->dropFields = $dropFields;

        return true;
    }
}
