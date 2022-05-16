<?php

/*
 * This file is part of the Kindy\EgyaptianEInvoice Package.
 *
 * (c) Ibrahim Abotaleb <admin@mrkindy.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Kindy\EgyaptianEInvoice\Document;


/**
 * Class DocumnetEntity
 *
 * ETAInvoice Documnet Entity object
 *
 * @package Kindy\EgyaptianEInvoice
 */
class DocumnetEntity
{

    /**
     * @param array $entity
     */
    public function __construct(array $entity,$type='issuer')
    {
        $this->address = new DocumentAddress($entity['address'],$type);
        $this->type = isset($entity['type'])?$entity['type']:'B';
        $this->id = isset($entity['id'])?$entity['id']:0;
        $this->name = isset($entity['name'])?$entity['name']:'unknown';
    }
}