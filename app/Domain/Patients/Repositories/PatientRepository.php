<?php

namespace App\Domain\Patients\Repositories;

use App\Core\Repository;
use App\Domain\Patients\Models\Patient;
use App\Http\Requests\Patient\StoreRequest;
use App\Http\Requests\Patient\UpdateRequest;
use App\Http\Resources\PatientResource;
use Closure;

class PatientRepository extends Repository
{
    /**
     * @var array<string>
     */
    protected array $validations = [
        StoreRequest::class,
        UpdateRequest::class,
    ];

    /**
     * @return mixed
     */
    protected function model()
    {
        return Patient::class;
    }

    /**
     * @return string
     */
    public function resource()
    {
        return PatientResource::class;
    }

    /**
     * Atualiza planos.
     *
     * @param  mixed  $model
     * @param  array  $plans
     * @return void
     */
    public function syncPlans(Patient $model, array $plans = [])
    {
        $items = collect($plans)
            ->mapWithKeys(fn ($plan) => [
                $plan['id'] => [
                    'contract_number' => $plan['contract_number'],
                ],
            ]);

        $model->plans()->sync($items);
    }

    /**
     * @return Closure
     */
    public function afterCreate()
    {
        return function (Patient $patient) {
            $this->syncPlans($patient, $this->getRequest()->input('plans'));

            $this->syncCollection(
                $patient,
                'phones',
                $this->getRequest()->input('phones'),
                fn ($item) => [
                    'number' => $item['number'],
                ]
            );
        };
    }

    /**
     * @return Closure
     */
    public function afterUpdate()
    {
        return function (Patient $patient) {
            $this->syncPlans($patient, $this->getRequest()->input('plans'));

            $this->syncCollection(
                $patient,
                'phones',
                $this->getRequest()->input('phones'),
                fn ($item) => [
                    'id' => $item['id'] ?? null,
                    'number' => $item['number'],
                ]
            );
        };
    }
}
