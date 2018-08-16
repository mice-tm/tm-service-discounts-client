<?php

namespace micetm\Clients\ServiceDiscounts\models;

class Discount
{
    const DISCOUNT_TYPE_AMOUNT = 'amount';
    const DISCOUNT_TYPE_PERCENT = 'percent';

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_DISABLED = 'disabled';
    const STATUS_USED = 'used';
    const STATUS_EXPIRED = 'expired';
    const STATUS_DELETED = 'deleted';

    const TYPE_SERVER = 'server';
    const TYPE_USER = 'user';
    const TYPE_TRIGGER = 'trigger';

    const NAMESPACE_PRODUCTS = 'products';
    const NAMESPACE_SERVICES = 'services';
    const NAMESPACE_BUNDLES = 'bundles';
    const NAMESPACE_MEMBERSHIPS = 'memberships';

    public $id;
    public $discount_value;
    public $discount_type;
    public $max_number_of_usages;
    public $created_at;
    public $start_at;
    public $stop_at;
    public $status;
    public $type;
    public $author;
    public $ignore_affiliate;
    public $set_affiliate;
    public $reason;
    public $used;
    public $tries;
    public $project;
    public $namespace;
    public $email;
    public $info;

    /** @var array */
    public $applicability;

    private static $discountTypes = [
        self::DISCOUNT_TYPE_AMOUNT => '$',
        self::DISCOUNT_TYPE_PERCENT => '%',
    ];

    private static $statuses = [
        self::STATUS_ACTIVE,
        self::STATUS_INACTIVE,
        self::STATUS_DISABLED,
        self::STATUS_USED,
        self::STATUS_EXPIRED,
        self::STATUS_DELETED,
    ];

    private static $types = [
        self::TYPE_SERVER => 'simple',
        self::TYPE_USER => 'simple',
        self::TYPE_TRIGGER => 'discount_link',
    ];

    private static $namespaces = [
        self::NAMESPACE_PRODUCTS,
        self::NAMESPACE_SERVICES,
        self::NAMESPACE_BUNDLES,
        self::NAMESPACE_MEMBERSHIPS,
    ];

    public function __construct($params = [])
    {
        $this->load($params);
    }

    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function load($params = [])
    {
        if (!is_array($params)) {
            return;
        }

        foreach ($params as $key => $value) {
            if (in_array($key, $this->attributes())) {
                $this->$key = $value;
            }
        }
    }

    public function getDiscountType()
    {
        return self::$discountTypes[$this->discount_type];
    }

    public function getType()
    {
        return self::$types[$this->type];
    }

    public function attributes()
    {
        return [
            'id',
            'discount_value',
            'discount_type',
            'max_number_of_usages',
            'created_at',
            'start_at',
            'stop_at',
            'status',
            'type',
            'author',
            'ignore_affiliate',
            'set_affiliate',
            'reason',
            'used',
            'tries',
            'project',
            'namespace',
            'info',
            'email',
        ];
    }
}
