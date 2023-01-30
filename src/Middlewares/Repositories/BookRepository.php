<?php

namespace Matvey\Test\Middlewares\Repositories;

use Matvey\Test\Models\Book\Record;
use Psr\Http\Message\RequestInterface;

class BookRepository
{

   public Record $record;

    public function __construct(Record $record)
    {
        $this->record = $record;
    }


    public function newRecord(RequestInterface $request): bool
    {
        $body = $request->getParsedBody();

        if ((isset($body['record'])) && (!empty($body['record']))) {
            $this->record->setName(name: $request->getAttribute('userName'))->setRecord($body['record'])->save();
            return true;
        }
        return false;
    }


}