<?php
/**
 * @package: Chapi
 *
 * @author:  msiebeneicher
 * @since:   2015-07-31
 *
 */


namespace Chapi\Service\JobIndex;


use Chapi\Component\Cache\CacheInterface;

class JobIndexService implements JobIndexServiceInterface
{
    const JOB_INDEX_CACHE_KEY = 'job.index';

    /**
     * @var CacheInterface
     */
    private $oCache;

    /**
     * @var array
     */
    private $aJobIndex = [];


    public function __construct(
        CacheInterface $oCache
    )
    {
        $this->oCache = $oCache;
        $this->aJobIndex = $this->getJobIndexFromStorage();
    }

    public function __destruct()
    {
        $this->setJobIndexToStorage();
    }

    public function addJob($sJobName)
    {
        $this->aJobIndex[$sJobName] = $sJobName;
        return $this;
    }

    public function addJobs(array $aJobNames)
    {
        foreach ($aJobNames as $_sJobName)
        {
            $this->addJob($_sJobName);
        }

        return $this;
    }

    public function removeJob($sJobName)
    {
        if (isset($this->aJobIndex[$sJobName]))
        {
            unset($this->aJobIndex[$sJobName]);
        }

        return $this;
    }

    public function removeJobs(array $aJobNames)
    {
        foreach ($aJobNames as $_sJobName)
        {
            $this->removeJob($_sJobName);
        }

        return $this;
    }

    public function resetJobIndex()
    {
        $this->oCache->delete(self::JOB_INDEX_CACHE_KEY);
        $this->aJobIndex = [];
        return $this;
    }

    public function getJobIndex()
    {
        return $this->aJobIndex;
    }

    private function getJobIndexFromStorage()
    {
        $_aJobIndex = $this->oCache->get(self::JOB_INDEX_CACHE_KEY);
        return (is_array($_aJobIndex)) ? $_aJobIndex : [];
    }

    private function setJobIndexToStorage()
    {
        if (!empty($this->aJobIndex))
        {
            $this->oCache->set(self::JOB_INDEX_CACHE_KEY, $this->aJobIndex);
        }
    }
}