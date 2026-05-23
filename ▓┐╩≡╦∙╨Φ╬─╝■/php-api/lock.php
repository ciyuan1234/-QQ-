<?php

/**
 * Redis 实现的自旋锁
 * @注意：如果该锁所使用Redis ID与其他RedModel使用ID相同，而其他RedModel开启了Multi，则导致该锁的提交也会挂起，而需要通过exec执行，所以最好锁使用的Redis为单独配置
 * @author FM
 * @example RedSpinlock::Me($lockKey)->lock($ms, $retry_interval);RedSpinlock::Me($lockKey)->unlock();
 */
class RedSpinlock
{
	private static $instances = array();
	private $k = NULL;
	
	/**
	 * Redis对象
	 * @var \Redis
	 */
	private $rd;
	
	public function __construct($k, $ip = '127.0.0.1', $port = 6379)
	{
		$this->k = $k;
		$this->rd = new Redis();
		$this->rd->connect($ip, $port);
	}
	
	/**
	 * 当该对象解构时，删除锁，避免占用锁
	 */
	public function __destruct()
	{
		$this->unlock();
	}
	
	/**
	 * 重新连接Redis
	 */
	protected function reconnRedis()
	{
	    $this->rd = NULL;
		$this->rd = new Redis();
		$this->rd->connect($ip, $port);
	    if ($this->rd) return TRUE;
	    return FALSE;
	}
	
	/**
	 * 获得RedSpinlock对象
	 * @param string $k
	 * @return RedSpinlock
	 */
	public static function Me($k, $ip = '127.0.0.1', $port = 6379)
	{
		$mId = $k;
		if (isset(self::$instances[$mId])===FALSE) {
			self::$instances[$mId] = new self($k, $Id, $persist);
		}
		return self::$instances[$mId];
	}
	
	/**
	 * 获取一个自旋锁
	 * @param number $expireMs 毫秒级的锁时间,默认3秒钟
	 * @param number $retry_interval 等待获取锁的间隔时间，默认1秒
	 * @return bool 返回TRUE，默认如果获取不到锁，会一直等待
	 */
	public function lock($expireMs = 3000, $retry_interval = 1, $retryTimes = -1)
	{
	    $retry_interval = intval($retry_interval);
		if ($retry_interval<1) $retry_interval = 1;
		
		if (!$this->rd->isConnected()) {
		    if (!$this->reconnRedis()) {
		        return FALSE;
		    }
		}
		
		$lock = $this->getLock($expireMs);
		
		if ($lock) {
		    return TRUE;
		}
		
		// 如果获取不到锁，则执行循环等待
		$retry = 0;
		$timesUp = TRUE;
		while ($timesUp) {
		    $lock = $this->getLock($expireMs);
			if ($lock) {
				return TRUE;
			}
			
			if ($retryTimes>0) {
			    $retry++;
			    $timesUp = $retry<$retryTimes;
			}
			
			sleep($retry_interval);
		}
		
		return FALSE;
	}
	
	private function getLock($expireMs)
	{
	    // $microtime = sprintf('%d', microtime(true)*1000);
	    try {
	        $microtime = time() * 1000;
	        $locktime = $microtime + $expireMs;
	        
	        $lock = $this->rd->setnx($this->k, $locktime);
	        
	        if (!$lock) {
	            $lockV = $this->rd->getSet($this->k, $locktime);
	            if ($lockV<=$microtime) {
	                $lock = 1;
	                $this->rd->pexpireAt($this->k, $locktime); // 设置过期时间
	            }
	        }
	        
	        return $lock;
	        
	    } catch (\RedisException $e) {
	        if ($this->reconnRedis()) {
	            $microtime = time() * 1000;
	            $locktime = $microtime + $expireMs;
	            
	            $lock = $this->rd->setnx($this->k, $locktime);
	            
	            if (!$lock) {
	                $lockV = $this->rd->getSet($this->k, $locktime);
	                if ($lockV<=$microtime) {
	                    $lock = 1;
	                    $this->rd->pexpireAt($this->k, $locktime); // 设置过期时间
	                }
	            }
	            
	            return $lock;
	        }
	    }
	    
	    return FALSE;
	}
	
	public function unlock()
	{
	    try {
	        $this->rd->del($this->k);
	    } catch (\RedisException $e) {
	        
	    }
	}
}