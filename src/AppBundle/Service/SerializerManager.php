<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Service;

use AppBundle\Service\Normalizers\DateTimeNormalizer;
use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Class SerializerManager
 * @package AppBundle\Service
 */
class SerializerManager
{

    /**
     * @param mixed $object
     * @param string $format
     * @param array $groups
     *
     * @return array|object
     */
    public static function normalize($object, $format = 'json', $groups = ['default'])
    {
        try {
            return SerializerManager::getSerializers()->normalize($object, $format);
        } catch (AnnotationException $e) {
            return [];
        }
    }

    /**
     * @return Serializer
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public static function getSerializers()
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));

        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new DateTimeNormalizer(), new ObjectNormalizer($classMetadataFactory)];

        $serializer = new Serializer($normalizers, $encoders);

        return $serializer;
    }
}