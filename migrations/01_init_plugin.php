<?php
class InitPlugin extends Migration
{
	function up() {
        Config::get()->create("DISK_FREE_SPACE_ALERT", array(
            'value' => 1024 * 1024 * 1024 * 2,
            'type' => "integer",
            'range' => "global",
            'section' => "global",
            'description' => "Root should be warned if the free disk space in /TMP is lower than x bytes?"
        ));
	}

    function down() {
        Config::get()->delete("DISK_FREE_SPACE_ALERT");
    }
}
