<?php

namespace jblond\math;

class Benchmark
{
    protected array $timingStartTimes;
    protected array $timingStopTimes;


    /**
     * benchmark::timer_start()
     * @access public
     * @param string $name
     */
    public function timerStart(string $name = 'default'): void
    {
        $this->timingStartTimes[$name] = explode(' ', microtime());
    }

    /**
     * benchmark::timer_stop()
     * @access public
     * @param string $name
     */
    public function timerStop(string $name = 'default'): void
    {
        $this->timingStopTimes[$name] = explode(' ', microtime());
    }

    /**
     * benchmark::timer_result()
     * @access public
     * @param string $name
     * @return float
     */
    public function timerResult(string $name = 'default'): float
    {
        if (!isset($this->timingStartTimes[$name])) {
            return 0;
        }
        $stop_time = $this->timingStopTimes[$name] ?? explode(' ', microtime());
        // do the big numbers first so the small ones aren't lost
        $current = $stop_time[1] - $this->timingStartTimes[$name][1];
        $current += $stop_time[0] - $this->timingStartTimes[$name][0];
        return $current;
    }
}
