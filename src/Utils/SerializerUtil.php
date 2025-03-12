<?php


namespace App\Utils;

use Symfony\Component\Serializer\SerializerInterface;

class SerializerUtil
{
    public function __construct(
        private readonly SerializerInterface $serializer
    ){}

    /**
     * serilized data
     * @param $data
     * @param string $group
     * @return array
     */
    public function serializeData($data, string $group): array
    {
        $jsonData = $this->serializer->serialize($data, 'json', ['groups' => $group]);

        return json_decode($jsonData, true);
    }

    /**
     * @param $data
     * @param string $dto
     * @param string $group
     * @return mixed|object|string
     */
    public function deserializeDataToDto($data, string $dto, string $group): mixed
    {
        return $this->serializer->deserialize($data, $dto, 'json', ['groups' => $group]);
    }
}
