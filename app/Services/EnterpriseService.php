<?php

namespace App\Services;

use App\Http\Dtos\EnterpriseCreateDTO;
use App\Http\Dtos\EnterpriseUpdateDTO;
use App\Models\Enterprise;
use Illuminate\Support\Collection;
use Nette\Schema\ValidationException;


class EnterpriseService
{
 public function create(EnterpriseCreateDTO $dto): Enterprise
 {
   $enterprise =  new Enterprise();

   $enterprise->name = $dto->name;
   $enterprise->cnpj = $dto->cnpj;
   $enterprise->password = $dto->password;

   $enterprise->save();

   return $enterprise;

  
   }

 public function readAll(): Collection
{
    return Enterprise::all();
}


public function findById(int $id): Enterprise
{

 return Enterprise::findOrFail($id);

}

public function update(Enterprise $enterprise, EnterpriseUpdateDTO $dto): Enterprise
    {
        if ($dto->name !== null) {
            $enterprise->name = $dto->name;
        }

        if ($dto->cnpj !== null) {
            $enterprise->cnpj = $dto->cnpj;
        }

        if ($dto->password !== null) {
            $enterprise->password = $dto->password;
        }

        $enterprise->save();

        return $enterprise;
    }

  public function delete(int $id): bool    
{

    $enterprise = $this->findById($id);

    return $enterprise->delete();
}

}