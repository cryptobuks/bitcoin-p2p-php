<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Networking\Messages;

use BitWasp\Bitcoin\Bloom\BloomFilter;
use BitWasp\Bitcoin\Networking\Message;
use BitWasp\Bitcoin\Networking\NetworkSerializable;
use BitWasp\Bitcoin\Serializer\Bloom\BloomFilterSerializer;
use BitWasp\Bitcoin\Networking\Serializer\Message\FilterLoadSerializer;
use BitWasp\Buffertools\BufferInterface;

class FilterLoad extends NetworkSerializable
{
    /**
     * @var BloomFilter
     */
    private $filter;

    /**
     * @param BloomFilter $filter
     */
    public function __construct(BloomFilter $filter)
    {
        $this->filter = $filter;
    }

    /**
     * @return string
     */
    public function getNetworkCommand(): string
    {
        return Message::FILTERLOAD;
    }

    /**
     * @return BloomFilter
     */
    public function getFilter(): BloomFilter
    {
        return $this->filter;
    }
    /**
     * @return BufferInterface
     */
    public function getBuffer(): BufferInterface
    {
        return (new FilterLoadSerializer(new BloomFilterSerializer()))->serialize($this);
    }
}
