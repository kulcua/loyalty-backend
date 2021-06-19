<?php

namespace Kulcua\Extension\Bundle\WarrantyBundle\ParamConverter;

use Kulcua\Extension\Component\Warranty\Domain\ReadModel\WarrantyDetails;
use Kulcua\Extension\Component\Warranty\Domain\ReadModel\WarrantyDetailsRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class WarrantyDetailsParamConverter.
 */
class WarrantyDetailsParamConverter implements ParamConverterInterface
{
    /**
     * @var WarrantyDetailsRepository
     */
    protected $repository;

    /**
     * WarrantyDetailsParamConverter constructor.
     *
     * @param WarrantyDetailsRepository $repository
     */
    public function __construct(WarrantyDetailsRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Stores the object in the request.
     *
     * @param Request        $request       The request
     * @param ParamConverter $configuration Contains the name, class and options of the object
     *
     * @return bool True if the object has been successfully set, else false
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $name = $configuration->getName();

        if (null === $request->attributes->get($name, false)) {
            $configuration->setIsOptional(true);
        }
        $value = $request->attributes->get($name);
        $object = $this->repository->find($value);

        if (null === $object && false === $configuration->isOptional()) {
            throw new NotFoundHttpException(sprintf('%s object not found.', $configuration->getClass()));
        }
        $request->attributes->set($name, $object);

        return true;
    }

    /**
     * Checks if the object is supported.
     *
     * @param ParamConverter $configuration Should be an instance of ParamConverter
     *
     * @return bool True if the object is supported, else false
     */
    public function supports(ParamConverter $configuration)
    {
        return $configuration->getClass() === WarrantyDetails::class;
    }
}
