<?php

namespace App\DataTables;

use App\Models\Project;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
// use Yajra\DataTables\Html\Editor\Editor;
// use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProjectsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->setRowId('id')
            ->addColumn('users', function ($row) {
                $users = $row->users->map(function ($user) {
                    return $user->name;
                })->implode(', ');
                return $users;
            })
            ->addColumn('edit', function ($row) {
                $editUrl = route('projects.edit', $row->id); // Replace 'your.edit.route' with your actual route
                return '<a href="' . $editUrl . '" class="btn btn-sm btn-primary">Edit</a>';
            })
            ->rawColumns(['edit']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Project $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('projects-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload'),
                [
                    'text' => 'Create New Project', // Button label
                    'class' => 'btn btn-primary', // Button classes (optional)
                    'action' => "function() { window.location.href = '" . route('projects.create') . "'; }", // JS action to redirect
                ],
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('name'),
            Column::make('users')
                ->title('Users')
                ->orderable(false)
                ->searchable(false),
            Column::make('start_date'),
            Column::make('end_date'),
            Column::make('edit')
                ->title('Actions')
                ->orderable(false)
                ->searchable(false),

        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Projects_' . date('YmdHis');
    }
}
