<?php

namespace App\Traits;

use Illuminate\Support\Facades\Schema;

trait IsSortAndFilterable
{
    /**
     * Method for return sorted and filtered models.
     *
     * @param array $data
     * @param array $searchColumns The columns to search on, if a search value is provided. If no columns are provided, then use the fillable property
     */
    public function getSortedAndFiltered(array $data, array $searchColumns = [], $exactMatch = false, $displayTrash = false)
    {
        $model = $this;

        if (isset($data['search']) && $data['search']) {

            // If there are no provided search columns, then get columns from db
            if (! $searchColumns) {
                if ($this->fillable) {
                    $searchColumns = $this->fillable;
                } else {
                    $searchColumns = Schema::getColumnListing($this->table);
                }
            }

            $model = $model::search($searchColumns, $data['search'], $exactMatch);
        }

        // This is based off how Vuetify returns sort data
        // $data['sortBy'] will be a list of columns to sort off of
        // $data['sortDesc'] is an array of boolean values, true meaning desc and false meaning asc
        // The keys between the two arrays correlate with each other, so if $data['sortDesc'][0] === true, then sort by $data['sortBy'][0] in desc order
        if (isset($data['sortBy'])) {
            foreach ($data['sortBy'] as $key => $column) {
                $sortDirection = 'asc';

                if ($data['sortDesc'][$key] === 1) {
                    $sortDirection = 'desc';
                }

                $model = $model->orderBy($column, $sortDirection);
            }
        }

        return ($displayTrash) ? $model->withTrashed() : $model;
    }
}
