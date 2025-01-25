<?php


return [
  "tin_reasons"=>[
      'reason_no_issue' => 'The country where the Account Holder is liable to pay tax does not issue TINs to its residents',
      'reason_unable' => 'The Account Holder is otherwise unable to obtain a TIN or equivalent number',
      'reason_not_required' => 'No TIN is required'
  ],
  "account_purposes" => [
        [
            'id' => 'custody',
            'value' => 'Custody',
            'label' => 'Custody'
        ],
        [
            'id' => 'asset_servicing',
            'value' => 'AssetServicing',
            'label' => 'Asset Servicing'
        ],
        [
            'id' => 'escrow',
            'value' => 'Escrow',
            'label' => 'Escrow'
        ],
        [
            'id' => 'investments',
            'value' => 'Investments',
            'label' => 'Investments'
        ],
        [
            'id' => 'treasury_services',
            'value' => 'TreasuryServices',
            'label' => 'Treasury Services'
        ],
        [
            'id' => 'other_purpose',
            'value' => 'Other',
            'label' => 'Other'
        ]
    ]
];
