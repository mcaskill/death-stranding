<?php

declare(strict_types=1);

namespace Bridges\Types;

/**
 * Default built-in types provided by Bridges.
 *
 * Categories:
 *
 * - AREA_* = Area Type (ZONE_*)
 * - ECOTOPE_* = Ecotope Type (ECO_*, ENV_*)
 * - ATTR_* = Attribute
 * - EVAL_* = Evaluation Category
 * - INFRA_* = Infrastructure Type (STRUCT_*, BLD_*)
 * - OBJ_* = Objective Type
 * - OBST_* = Obstacle
 * - ORDER_* = Order Type
 * - DELIV_* = Delivery Type
 * - POI_* = Point of Interest
 * - REGION_* = Region (RGN_*)
 * - REWARD_* = Reward Type (RWD_*)
 */
final class Types
{
    public const AREA_BT                       = 'AREA_BT';
    public const AREA_MULE                     = 'AREA_MULE';
    public const AREA_TERRORIST                = 'AREA_TERRORIST';
    public const ECOTOPE_CRATER                = 'ECOTOPE_CRATER';
    public const ECOTOPE_FOREST                = 'ECOTOPE_FOREST';
    public const ECOTOPE_MOUNTAIN              = 'ECOTOPE_MOUNTAIN';
    public const ECOTOPE_RUINS                 = 'ECOTOPE_RUINS';
    public const ECOTOPE_VOG                   = 'ECOTOPE_VOG';
    public const ECOTOPE_WATER                 = 'ECOTOPE_WATER';
    public const ATTR_LIKES                    = 'ATTR_LIKES';
    public const ATTR_MASS_KG                  = 'ATTR_MASS_KG';
    public const ATTR_WEIGHT                   = 'ATTR_WEIGHT';
    public const ATTR_CARGO                    = 'ATTR_CARGO';
    public const EVAL_BRIDGE_LINK              = 'EVAL_BRIDGE_LINK';
    public const EVAL_CARGO_CONDITION          = 'EVAL_CARGO_CONDITION';
    public const EVAL_MISCELLANEOUS            = 'EVAL_MISCELLANEOUS';
    public const EVAL_DELIVERY_VOLUME          = 'EVAL_DELIVERY_VOLUME';
    public const EVAL_DELIVERY_TIME            = 'EVAL_DELIVERY_TIME';
    public const INFRA_CITY                    = 'INFRA_CITY';
    public const INFRA_DISTRIBUTION            = 'INFRA_DISTRIBUTION';
    public const INFRA_INCINERATOR             = 'INFRA_INCINERATOR';
    public const INFRA_MULE_CAMP               = 'INFRA_MULE_CAMP';
    public const INFRA_OUTPOST                 = 'INFRA_OUTPOST';
    public const INFRA_SHELTER                 = 'INFRA_SHELTER';
    public const INFRA_TERRORIST_CAMP          = 'INFRA_TERRORIST_CAMP';
    public const INFRA_WAYSTATION              = 'INFRA_WAYSTATION';
    public const OBJ_DISPOSAL                  = 'OBJ_DISPOSAL';
    public const OBJ_MIN_CONDITION             = 'OBJ_MIN_CONDITION';
    public const OBJ_MIN_QUANTITY              = 'OBJ_MIN_QUANTITY';
    public const OBJ_MIN_WEIGHT                = 'OBJ_MIN_WEIGHT';
    public const OBJ_RECOVERY                  = 'OBJ_RECOVERY';
    public const OBJ_TIMED                     = 'OBJ_TIMED';
    public const OBST_REMOTE_AREA              = 'OBST_REMOTE_AREA';
    public const OBST_MOUNTAINOUS              = 'OBST_MOUNTAINOUS';
    public const OBST_SNOWY_MOUNTAIN           = 'OBST_SNOWY_MOUNTAIN';
    public const OBST_CARGO_CARRY_BY_HAND      = 'OBST_CARGO_CARRY_BY_HAND';
    public const OBST_CARGO_COLLECTION         = 'OBST_CARGO_COLLECTION';
    public const OBST_CARGO_COLLECTION_VOG     = 'OBST_CARGO_COLLECTION_VOG';
    public const OBST_CARGO_COLLECTION_BT      = 'OBST_CARGO_COLLECTION_BT';
    public const OBST_CARGO_FRAGILE            = 'OBST_CARGO_FRAGILE';
    public const OBST_CARGO_KEEP_FLAT          = 'OBST_CARGO_KEEP_FLAT';
    public const OBST_CARGO_RECOVERY_MULE      = 'OBST_CARGO_RECOVERY_MULE';
    public const OBST_CARGO_RECOVERY_TERRORIST = 'OBST_CARGO_RECOVERY_TERRORIST';
    public const OBST_CHILLED_DELIVERY         = 'OBST_CHILLED_DELIVERY';
    public const OBST_DO_NOT_SUBMERGE          = 'OBST_DO_NOT_SUBMERGE';
    public const ORDER_ACTIVITY                = 'ORDER_ACTIVITY';
    public const ORDER_CHILLED                 = 'ORDER_CHILLED';
    public const ORDER_FRAGILE                 = 'ORDER_FRAGILE';
    public const ORDER_REORDER                 = 'ORDER_REORDER';
    public const ORDER_URGENT                  = 'ORDER_URGENT';
    public const ORDER_STANDARD                = 'ORDER_STANDARD';
    public const ORDER_STORY                   = 'ORDER_STORY';
    public const DELIV_STANDARD                = 'DELIV_STANDARD';
    public const DELIV_PREMIUM                 = 'DELIV_PREMIUM';
    public const POI_FOREST                    = 'POI_FOREST';
    public const POI_GRAVEYARD                 = 'POI_GRAVEYARD';
    public const POI_HOT_SPRING                = 'POI_HOT_SPRING';
    public const POI_LAKE                      = 'POI_LAKE';
    public const POI_LANDSCAPE                 = 'POI_LANDSCAPE';
    public const POI_MOUNTAIN                  = 'POI_MOUNTAIN';
    public const POI_RAVINE                    = 'POI_RAVINE';
    public const REGION_CENTRAL                = 'REGION_CENTRAL';
    public const REGION_EASTERN                = 'REGION_EASTERN';
    public const REGION_WESTERN                = 'REGION_WESTERN';
    public const REWARD_ACHIEVEMENT            = 'REWARD_ACHIEVEMENT';

    private function __construct()
    {
    }
}
