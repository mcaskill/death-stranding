<?php

declare(strict_types=1);

namespace Bridges\Types;

/**
 * Base types provided by Bridges.
 */
final class Type
{
    public const UNDEFINED_BSLN                = 'X-UCA-00-000';
    public const UNDEFINED_VALUE               = '�';
    public const AREA_BT                       = 'BT Territory';
    public const AREA_MULE                     = 'MULE Territory';
    public const AREA_TERRORIST                = 'Terrorist Territory';
    public const ECOTOPE_CRATER                = 'Crater';
    public const ECOTOPE_FOREST                = 'Forest';
    public const ECOTOPE_MOUNTAIN              = 'Mountain';
    public const ECOTOPE_RUINS                 = 'Ruins';
    public const ECOTOPE_VOG                   = 'Vog';
    public const ECOTOPE_WATER                 = 'Water';
    public const ATTR_LIKES                    = 'Likes';
    public const ATTR_MASS_KG                  = 'Kilogram';
    public const ATTR_WEIGHT                   = 'Weight';
    public const ATTR_CARGO                    = 'Cargo';
    public const EVAL_BRIDGE_LINK              = 'Bridge Link';
    public const EVAL_CARGO_CONDITION          = 'Cargo Condition';
    public const EVAL_MISCELLANEOUS            = 'Miscellaneous';
    public const EVAL_DELIVERY_VOLUME          = 'Delivery Volume';
    public const EVAL_DELIVERY_TIME            = 'Delivery Time';
    public const INFRA_CITY                    = 'Knot City';
    public const INFRA_DISTRIBUTION            = 'Distribution Center';
    public const INFRA_INCINERATOR             = 'Incinerator';
    public const INFRA_MULE_CAMP               = 'MULE Camp';
    public const INFRA_OUTPOST                 = 'Outpost';
    public const INFRA_SHELTER                 = 'Shelter';
    public const INFRA_TERRORIST_CAMP          = 'Terrorist Camp';
    public const INFRA_WAYSTATION              = 'Waystation';
    public const OBJ_DISPOSAL                  = 'Disposal';
    public const OBJ_MIN_CONDITION             = 'Condition';
    public const OBJ_MIN_QUANTITY              = 'Quantity';
    public const OBJ_MIN_WEIGHT                = 'Weight';
    public const OBJ_RECOVERY                  = 'Recovery';
    public const OBJ_TIMED                     = 'Timed';
    public const OBST_REMOTE_AREA              = 'Remote Area';
    public const OBST_MOUNTAINOUS              = 'Mountainous Area';
    public const OBST_SNOWY_MOUNTAIN           = 'Snowy Mountain Area';
    public const OBST_CARGO_CARRY_BY_HAND      = 'Fragile Cargo (Carry by Hand)';
    public const OBST_CARGO_COLLECTION         = 'Cargo Collection';
    public const OBST_CARGO_COLLECTION_VOG     = 'Cargo Collection from the Vog';
    public const OBST_CARGO_COLLECTION_BT      = 'Cargo Collection from a BT Area';
    public const OBST_CARGO_FRAGILE            = 'Fragile Cargo';
    public const OBST_CARGO_KEEP_FLAT          = 'Fragile Cargo (Keep Flat)';
    public const OBST_CARGO_RECOVERY_MULE      = 'Cargo Recovery from a MULE Camp';
    public const OBST_CARGO_RECOVERY_TERRORIST = 'Cargo Recovery from a Terrorists';
    public const OBST_CHILLED_DELIVERY         = 'Chilled Delivery';
    public const OBST_DO_NOT_SUBMERGE          = 'Do Not Submerge';
    public const ORDER_ACTIVITY                = 'Activity';
    public const ORDER_CHILLED                 = 'Chilled Order';
    public const ORDER_FRAGILE                 = 'Fragile Order';
    public const ORDER_REORDER                 = 'Re-Order';
    public const ORDER_URGENT                  = 'Urgent Order';
    public const ORDER_STANDARD                = 'Standard Order';
    public const ORDER_STORY                   = 'Story Order';
    public const DELIV_STANDARD                = 'Standard Delivery';
    public const DELIV_PREMIUM                 = 'Premium Delivery';
    public const POI_FOREST                    = 'Forest';
    public const POI_GRAVEYARD                 = 'Graveyard';
    public const POI_HOT_SPRING                = 'Hot Spring';
    public const POI_LAKE                      = 'Lake';
    public const POI_LANDSCAPE                 = 'Landscape';
    public const POI_MOUNTAIN                  = 'Mountain';
    public const POI_RAVINE                    = 'Ravine';
    public const REGION_CENTRAL                = 'Central Region';
    public const REGION_EASTERN                = 'Eastern Region';
    public const REGION_WESTERN                = 'Western Region';
    public const REWARD_ACHIEVEMENT            = 'Achievement';

    private function __construct()
    {
    }

    public static function getConst(string $name)
    {
        if (empty($name)) {
            return null;
        }

        return constant("self::{$name}") ?? null;
    }
}
