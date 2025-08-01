<?php

if (! function_exists('settings')) {
    /**
     * Get / set the specified settings value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array|string  $key
     * @param  mixed  $default
     * @return mixed
     */
    function settings($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('anlutro\LaravelSettings\SettingStore');
        }

        return app('anlutro\LaravelSettings\SettingStore')->get($key, $default);
    }
}
if (! function_exists('rand_region_numbers')) {
    function rand_region_numbers($max, $count)
    {
        if (!is_numeric($max) || !is_numeric($count))
        {
            return [0];
        }
        if ($max < $count)
        {
            return [$count];
        }
        $nums = [];
        $total = $max; // totalcount
        for ($i=0;$i<$count-1;$i++)
        {
            $c = rand(1, $total/($count-1-$i));
            $nums[] = $c;
            $total = $total - $c;
        }
        if ($total == 0)
        {
            $nums[] = 1;
        }
        else
        {
            $nums[] = $total;
        }
        return $nums;
    }
}

if (! function_exists('argon_route')) {
    function argon_route($name, $parameters = [], $absolute = true)
    {
        $parameters = array_merge([
            'slug' => config('app.slug'),
        ], array_wrap($parameters));

        return route($name, $parameters, $absolute);
    }
}

if (! function_exists('server_stat')) {
    function server_stat()
    {
        $server_check_version = '1.0.4';
        $start_time = microtime(TRUE);

        $operating_system = PHP_OS_FAMILY;

        if ($operating_system === 'Windows') {
            // Win CPU
            $wmi = new COM('WinMgmts:\\\\.');
            $cpus = $wmi->InstancesOf('Win32_Processor');
            $cpuload = 0;
            $cpu_count = 0;
            foreach ($cpus as $key => $cpu) {
                $cpuload += $cpu->LoadPercentage;
                $cpu_count++;
            }
            // WIN MEM
            $res = $wmi->ExecQuery('SELECT FreePhysicalMemory,FreeVirtualMemory,TotalSwapSpaceSize,TotalVirtualMemorySize,TotalVisibleMemorySize FROM Win32_OperatingSystem');
            $mem = $res->ItemIndex(0);
            $memtotal = round($mem->TotalVisibleMemorySize / 1000000,2);
            $memavailable = round($mem->FreePhysicalMemory / 1000000,2);
            $memused = round($memtotal-$memavailable,2);
            // WIN CONNECTIONS
            $connections = shell_exec('netstat -nt | findstr :80 | findstr ESTABLISHED | find /C /V ""'); 
            $totalconnections = shell_exec('netstat -nt | findstr :80 | find /C /V ""');
        } else {
            // Linux CPU
            $load = sys_getloadavg();
            $cpuload = $load[0];
            // Linux MEM
            $free = shell_exec('free');
            $free = (string)trim($free);
            $free_arr = explode("\n", $free);
            $mem = explode(" ", $free_arr[1]);
            $mem = array_filter($mem, function($value) { return ($value !== null && $value !== false && $value !== ''); }); // removes nulls from array
            $mem = array_merge($mem); // puts arrays back to [0],[1],[2] after 
            $memtotal = round($mem[1] / 1000000,2);
            $memused = round($mem[2] / 1000000,2);
            $memfree = round($mem[3] / 1000000,2);
            $memshared = round($mem[4] / 1000000,2);
            $memcached = round($mem[5] / 1000000,2);
            $memavailable = round($mem[6] / 1000000,2);
            // Linux Connections
            $connections = `netstat -ntu | grep :80 | grep ESTABLISHED | grep -v LISTEN | awk '{print $5}' | cut -d: -f1 | sort | uniq -c | sort -rn | grep -v 127.0.0.1 | wc -l`; 
            $totalconnections = `netstat -ntu | grep :80 | grep -v LISTEN | awk '{print $5}' | cut -d: -f1 | sort | uniq -c | sort -rn | grep -v 127.0.0.1 | wc -l`; 
        }

        $memusage = round(($memused/$memtotal)*100);



        $phpload = round(memory_get_usage() / 1000000,2);

        $diskfree = round(disk_free_space(".") / 1000000000);
        $disktotal = round(disk_total_space(".") / 1000000000);
        $diskused = round($disktotal - $diskfree);

        $diskusage = round($diskused/$disktotal*100);

        if ($memusage > 85 || $cpuload > 85 || $diskusage > 85) {
            $trafficlight = 'red';
        } elseif ($memusage > 50 || $cpuload > 50 || $diskusage > 50) {
            $trafficlight = 'orange';
        } else {
            $trafficlight = '#2F2';
        }

        $end_time = microtime(TRUE);
        $time_taken = $end_time - $start_time;
        $total_time = round($time_taken,4);

        return [
            "ram" => $memusage,
            "cpu" => $cpuload,
            "disk" => $diskusage,
            "connections" => $totalconnections,

            "ramtotal" => $memtotal,
            "ramused" => $memused,
            "ramavailable" => $memavailable,

            "diskfree" => $diskfree,
            "diskused" => $diskused,
            "disktotal" => $disktotal,
            
        ];
    }
}