<?php

namespace App\Services\Transaction;

use App\Models\User;
use Ship\Containers\PaymentMethodSetting\Components\Collections\PaymentMethodSettingCollection;
use Ship\Containers\PaymentMethodSetting\Components\PaymentMethodSetting;

class MethodService
{
    public function getMethodCollection(User $user, $brand)
    {
        $methods = $user
            ->methods()
            ->where('subtipo', $brand)
            ->where('metodopessoa.status', '>=', '1')
            ->orderBy('metodopessoa.prioridade', 'DESC')
            ->get();

        $methodCollection = new PaymentMethodSettingCollection();
        foreach ($methods as $method) {
            $setting = (new PaymentMethodSetting())
                ->setCompany($method->empresa)
                ->setType($method->tipo)
                ->setSubtype($method->subtipo)
                ->setAffiliation($method->pivot->afiliacao)
                ->setKey($method->pivot->chave)
                ->setUser($method->pivot->usuarioCaptura)
                ->setPassword($method->pivot->senhaCaptura)
                ->setEnvironment(($method->pivot->status == 2) ? 1 : 0)
                ->setKeyExpireAt($method->pivot->chave_vencimento)
                ->setAuthorizationMode($method->pivot->autorizacao)
                ->setAuthenticationMode($method->pivot->autenticacao)
                ->setInstallmentMode($method->pivot->tipoparcelamento)
                ->setCapture(($method->pivot->capturaautomatica == 'S') ? true : false)
                ->setMinimumAmount($method->pivot->valorminimo)
                ->setMaximumAmount($method->pivot->valormaximo)
                ->setMaximumInstallmentAmount($method->pivot->maxparcela)
                ->setPriority($method->pivot->prioridade)
                ->setHasAnotherAcquirer(($method->pivot->outrometodo == 'S') ? true : false)

                ->setAgencyNumber("{$method->pivot->agencia}{$method->pivot->dvAgencia}")
                ->setAccountNumber("{$method->pivot->conta}{$method->pivot->dvConta}")
                ->setAgreementNumber($method->pivot->convenio)
                ->setAssignorNumber("{$method->pivot->codigodecedente}{$method->pivot->dvcodigodecedente}")
                ->setWalletNumber($method->pivot->carteira)
                ->setStartNumber($method->pivot->inicionossonumero)
                ->setExpiryDays($method->pivot->prazo)
                ->setTaxAmount($method->pivot->taxa)
                ->setFineAmount($method->pivot->multa)
                ->setUrlLogo($method->pivot->urllogomarca)
                ->credential();

            if ($setting->getMaximumInstallmentAmount() > 0 && $setting->getMaximumInstallmentAmount() < $transactionPayload->getOrder()->getInstallments()) {
                continue;
            }

            if ($setting->getMinimumAmount() > 0 && $setting->getMinimumAmount() > $transactionPayload->getOrder()->getAmount()) {
                continue;
            }

            if ($setting->getMaximumAmount() > 0 && $setting->getMaximumAmount() < $transactionPayload->getOrder()->getAmount()) {
                continue;
            }

            $methodCollection->addItem($setting);
        }

        return $methodCollection;
    }
}
