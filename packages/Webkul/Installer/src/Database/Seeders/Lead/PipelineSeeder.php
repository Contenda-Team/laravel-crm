<?php

namespace Webkul\Installer\Database\Seeders\Lead;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PipelineSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @param  array  $parameters
     * @return void
     */
    public function run($parameters = [])
    {
        DB::table('lead_pipelines')->delete();

        DB::table('lead_pipeline_stages')->delete();

        $now = Carbon::now();

        $defaultLocale = $parameters['locale'] ?? config('app.locale');

        DB::table('lead_pipelines')->insert([
            [
                'id'         => 1,
                'name'       => trans('installer::app.seeders.lead.pipeline.default', [], $defaultLocale),
                'is_default' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('lead_pipeline_stages')->insert($data = [
            [
                'id'               => 2,
                'code'             => 'follow-up',
                'name'             => trans('installer::app.seeders.lead.pipeline.pipeline-stages.follow-up', [], $defaultLocale),
                'probability'      => 100,
                'sort_order'       => 1,
                'lead_pipeline_id' => 1,
            ], [
                'id'               => 3,
                'code'             => 'prospect',
                'name'             => trans('installer::app.seeders.lead.pipeline.pipeline-stages.prospect', [], $defaultLocale),
                'probability'      => 100,
                'sort_order'       => 2,
                'lead_pipeline_id' => 1,
            ], [
                'id'               => 4,
                'code'             => 'negotiation',
                'name'             => trans('installer::app.seeders.lead.pipeline.pipeline-stages.negotiation', [], $defaultLocale),
                'probability'      => 100,
                'sort_order'       => 3,
                'lead_pipeline_id' => 1,
            ], 
        ]);
    }
}
