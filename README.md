Bridges HQ / Standard Orders
============================

> Keep on Keepin' On, Porter!

<cite>Death Stranding</cite> (<span lang="ja">デス・ストランディング</span>) is a video game developed by Kojima Productions.

---

This project is a tool for consulting and managing deliveries within the game's world.

This framework is based on the incredible work of @spenjer1, @Miloby, and @Helel_Ben who originally compiled the game's standard orders and published them on Reddit <sup>[[1][reddit-e3g1q8]]</sup> <sup>[[2][reddit-e3g2yg]]</sup> as a collection of HTML tables.

I've verified the information and corrected errors, adding missing datum, and resolved some inconsistencies.

The tables have been converted into JSON collections:

* _Places_ — A list of facilities, shelters, camps, BT areas, and points of interest.
* _Orders_ — A list of standard and premium deliveries and extracurricular activities.

A basic HTML table providing a human-readable look at the dataset is available in the `demo/html` branch or at [mcaskill.ca/death-stranding](https://mcaskill.ca/death-stranding/).

## Schema

The structure of places and orders is a work in progress and open to suggestions.

### Place

<details><summary>Example of a place</summary>

```json
{
    "id": "6fa149f8-2559-4282-8948-596dc624d578",
    "updated_at": "2020-07-12T04:30:00Z",
    "bsln": "UCA-01-003",
    "knot": 2,
    "name": "Capital Knot City",
    "details": null,
    "type": "INFRA_CITY",
    "region": "REGION_EASTERN",
    "amenities": [],
    "tags": [
        "ORDER_STORY",
        "ORDER_STANDARD"
    ],
    "geometry": {
        "point": {
            "lng": 1431.92,
            "lat": 701.45
        }
    }
}
```
</details>

### Order

<details><summary>Example of an order</summary>

```json
{
    "id": "615fff86-a886-4bb8-b262-e92eda9b6015",
    "updated_at": "2020-07-12T04:30:00Z",
    "bson": "306",
    "type": "ORDER_STANDARD",
    "reorder": true,
    "urgent": false,
    "name": "[RE-ORDER] Retrieval: Camera",
    "available_at": {
        "place_id": "70d1a23d-c33f-4b4d-9950-c66b20885d68"
    },
    "collection_at": {
        "place_id": "04b44723-e048-4bd9-8a54-b284d7ba7adb"
    },
    "delivery_at": {
        "place_id": "70d1a23d-c33f-4b4d-9950-c66b20885d68"
    },
    "objectives_text": "Recovery (Terrorists), Fragile, Mountainous, Condition, Quantity (<50%, 1 / <20%, 1+)",
    "objectives": [
        {
            "type": "OBJ_MIN_QUANTITY",
            "standard": "1",
            "premium": "1+"
        },
        {
            "type": "OBJ_MIN_CONDITION",
            "standard": "<50%",
            "premium": "<20%"
        }
    ],
    "obstacles": [
        {
            "type": "OBST_CARGO_RECOVERY_TERRORIST"
        },
        {
            "type": "OBST_CARGO_FRAGILE"
        },
        {
            "type": "OBST_MOUNTAINOUS"
        }
    ],
    "category": "EVAL_MISCELLANEOUS",
    "maxlikes": {
        "standard": 43,
        "premium": 54
    },
    "weight": 1.5,
    "content": [
        "S-1"
    ]
}
```
</details>

### Notes

* The **Bridges Standard Order Number** (BSON) is a unique number used to identify an in-game order. The term itself is invented for the purpose of the JSON schema.
* The **Bridges Standard Location Number** (BSLN) is a unique identifier used to identify an in-game location. The term itself is invented for the purpose of the JSON schema.
* The `updated_at` property on orders indicates when they were last checked during my playthrough.
* The `id` property places and orders indicates an independent unique number to ensure elements are identified to coordinate relationships.

## Sources

* [FULL Standard Order List by Order #, Part 1][reddit-e3g1q8] by @spenjer1, @Miloby, and @Helel_Ben on Reddit. Published 2019-11-29. Retrieved 2019-05-02.
* [FULL Standard Order List by Order #, Part 2][reddit-e3g2yg] by @spenjer1, @Miloby, and @Helel_Ben on Reddit. Published 2019-11-29. Retrieved 2019-05-02.
* [Condensed List of Standard Orders][reddit-e1ig81] by @spenjer1, @Miloby, and @Helel_Ben on Reddit. Published 2019-11-25.
* [Standard Orders List][gamefaqs-78100] by @spenjer1, @Miloby, and @Helel_Ben on GameFAQs. Published 2019-12-12.
* [List of Standard Orders][ign-orders] by IGN
* [World Map][ign-world] by IGN
* [World Map][mapgenie-world] by Map Genie

## Acknowledgements

* [Death Stranding Delivery Checker](https://nessworthy.me/deathstranding/) by Sean Nessworthy
* [Death Stranding Delivery Checker](https://github.com/wagawo/derivery-checker) by @wagawo and @elriea2000 — Initial inspiration for this project.
* [Death Stranding Zipline Network Tool](https://github.com/smcnabb/death-stranding-zipline-network) by @smcnabb — Coordinates and three-letter facility codes.

## Legal

BRIDGES HQ is released to the Public Domain.

DEATH STRANDING is a trademark of Sony Interactive Entertainment LLC. Created and developed by Kojima Productions. All trademarks are the property of their respective owners.

This project is a fan resource and in no way affiliated with Sony or Kojima Productions.

[kojima-ds]: http://www.kojimaproductions.jp/death_stranding.html
[sony-ds]:   https://www.playstation.com/en-us/games/death-stranding-ps4/

[ign-orders]:    https://www.ign.com/wikis/death-stranding/List_of_Standard_Orders
[reddit-e1ig81]: https://www.reddit.com/r/DeathStranding/comments/e1ig81/spoilers_condensed_list_of_standard_orders/
[reddit-e3g1q8]: https://www.reddit.com/r/DeathStranding/comments/e3g1q8/full_standard_order_list_by_order_spoilers/
[reddit-e3g2yg]: https://www.reddit.com/r/DeathStranding/comments/e3g2yg/full_standard_order_list_by_order_part_2_spoilers/
[gamefaqs-78100]: https://gamefaqs.gamespot.com/ps4/184428-death-stranding/faqs/78100

[ign-world]:      https://www.ign.com/maps/death-stranding/world
[mapgenie-world]: https://mapgenie.io/death-stranding/maps/world
