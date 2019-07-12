<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // É possível checar aqui se o usuário tem permissão suficientes para criar uma nova transação
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // ORDER
            'order'                              => 'required|array',
            'order.orderId'                      => 'required|string|max:20',
            'order.amount'                       => 'required|numeric',
            'order.currency'                     => 'nullable|in:BRL',
            'order.installments'                 => 'required_if:payment.type,creditcard|integer|max:12|min:1',
            'order.discount'                     => 'numeric',
            'order.freight'                      => 'numeric',
            'order.freightDescription'           => 'string|max:200',
            'order.fingerprint'                  => 'string|max:200',
            'order.ip'                           => 'string|ip',
            'order.callbackUrl'                  => 'url',
            'order.antifraud'                    => 'bool',

            //CUSTOMER
            'order.customer'                     => 'array',
            'order.customer.visitorId'           => 'string',
            'order.customer.name'                => 'required_with:customer|string|min:4|max:120',
            'order.customer.email'               => 'email',
            'order.customer.phone'               => 'phone',
            'order.customer.cpfCnpj'             => 'cpf',
            'order.customer.birthdate'           => 'date_format:Y-m-d',

            // BILLING ADDRESS
            'order.customer.billing.zipcode'     => 'zip_code',
            'order.customer.billing.street'      => 'string|max:120',
            'order.customer.billing.number'      => 'string|max:8',
            'order.customer.billing.district'    => 'string|max:20',
            'order.customer.billing.complement'  => 'nullable|string|max:20',
            'order.customer.billing.city'        => 'string|max:30',
            'order.customer.billing.state'       => 'string|size:2',

            // SHIPPING ADDRESS
            'order.customer.shipping.zipcode'    => 'zip_code',
            'order.customer.shipping.street'     => 'string|max:120',
            'order.customer.shipping.number'     => 'string|max:8',
            'order.customer.shipping.district'   => 'string|max:20',
            'order.customer.shipping.complement' => 'nullable|string|max:20',
            'order.customer.shipping.city'       => 'string|max:30',
            'order.customer.shipping.state'      => 'string|size:2',

            //PRODUCTS
            'order.products'                     => 'array',
            'order.products.*.name'              => 'required_with:products|string|max:100',
            'order.products.*.unitPrice'         => 'required_with:products|numeric',
            'order.products.*.quantity'          => 'required_with:products|integer',
            'order.products.*.sku'               => 'string|max:50',
            'order.products.*.description'       => 'string|max:255',

            // PAYMENT
            'payment.type'                       => 'required|string|in:creditcard,debitcard,boleto',
            'payment.creditcard.brand'           => 'required|string',
            'payment.creditcard.token'           => 'string',
            'payment.creditcard.number'          => 'creditcard',
            'payment.creditcard.holder'          => 'required_with:payment.creditcard.number|string|max:40',
            'payment.creditcard.month'           => 'required_with:payment.creditcard.number|digits:2',
            'payment.creditcard.year'            => 'required_with:payment.creditcard.number|digits:4',
            'payment.creditcard.cvv'             => 'required_with:payment.creditcard.number|digits_between:3,4',

            'payment.boleto.company'             => 'required_if:payment.type,boleto|string',
            'payment.boleto.expiryDate'          => 'required_if:payment.type,boleto|date_format:Y-m-d',
            'payment.boleto.instructions'        => 'array',
            'payment.boleto.instructions.*'      => 'required_with:payment.boleto.instructions|string|max:120',
            'payment.boleto.demonstratives'      => 'array',
            'payment.boleto.demonstratives.*'    => 'required_with:payment.boleto.demonstratives|string|max:120',
        ];
    }
}
