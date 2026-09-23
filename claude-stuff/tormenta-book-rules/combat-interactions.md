This file has Tormenta20 rules about combat from the book and some explanations between parenthesis by me.

Attribute Addition to Damage:
Dano com Arma Corpo a Corpo ou de Arremesso = Dano da Arma + Força do Atacante
(meele or thrown = always STR, unless some power changes it to another attribute)
Dano com Arma de Disparo = Dano da Arma
(from the start, fired weapons dont add DEX)

---

Weapon Proficiency:
If a character is wielding a weapon that he does not have the proficency power for, they receive a penalty to hit.
mod_hit add value -5.
Same penalty if its a marcial weapon or if it's an exotica. The deal with exotica weapons is that they are kinda stronger but you as a player "waste" a power slot by choosing their specific proficiência. That's the tradeoff offered to the player (nothing to do with the app, just clearing it out.)

---

Wielding Weapons:
Like previously stated. A character can wield a weapon they don't have the proficiência power for, this just incurs a -5 penalty to their hit.
Characters can't wield 2 two handed weapons obviously (even if they have 4 hands. Hands 3 and 4 can only hold "leve" weapons anyway. The power that adds those hands states this.)
A character can wield a weapon that's one size above the character's current size with a penalty of -5 to attack. (Empunhadura Poderosa reduces the penalty to -2.)
A character CANNOT wield a weapon that's two sizes above the character's current size.
Down below is the character size/weapon size charting.

This is the table for the natural grips:
Minusculo -> Reduzida
Pequeno -> Normal
Medio -> Normal
Grande -> Aumentada
Enorme -> Aumentada
Colossal -> Gigante

So,
-2 -> -1
-1 -> 0
0 -> 0
+1 -> +1
+2 -> +1
+3 -> +2

So, following this example, a grande character can wield aumentada (its natural one) and aumentada (it'd be it's +1 size weapon category with -5 penalty. Since it's the same weapon size, no penalty but also no gain. They simply CANNOT wield a gigante weapon. It's designed for 2 sizes above a grande character.)

---

Dual Wielding:
A character can wield two one_hand weapons when they have two hands. Theres a caveat:
Naturally they CANNOT wield one_hand weapons in both hands. 
They can ONLY wield a one_hand in one hand and a "leve" in the other hand. 
They can only wield two one_hand weapons (no "leve" distinction) if they have the power "Arma Secundária Grande". This must be gated in the equip UI when the character is choosing a hand to wield a weapon.

By the book, even if the character is wielding two weapons, they can only attack with both in the same turn if they have "ambidestria" or "estilo de duas mãos". This does NOT matter to us since in the app they choose with what hand they are attacking in that moment. We do not take action economy into account.
Ambidestria and Estilo de Duas Mãos do the same thing. If you have one of them, it enables this dual-attack action incurring a -2 to hit penalty. If a character has both, they simply have no penalty for this dual-hit action.
Basically: character has ambi OR estilo, all their attacks in that turn receive a -2 penalty. They have both, no penalty. For us in the APP, this is a simple self-report in the hit screen. Ambi/estilo show up as a checkbox, user reports if they are doing a dual-attack. When character has both, no checkbox shows up since theres no penalty to self-report.

---

Critical Strikes:
Only weapon die are multiplied by the current multiplier for that selected weapon (weapon multiplier + whatever else is adding to it)
Extra die do not get multiplied by the current multiplier.
There's only one exception which is an enchantment called Lacinante. It adds a fixed mod_dmg that explicitly says it IS multiplied when a critical hit lands.

---

More Specific Rules:

Melee weapons:
They don't have any intrisic rules to them, besides what's stated above.

Thrown weapons:
Do not mix these with FIRED weapons. They are treated differently in Tormenta20.

Fired weapons:
1-When user is targetting an enemy thats involved in melee combat, according to the book, the character receives a -5 penalty to hit. (a power called Disparo Preciso removes this penalty). In the app, this is a self-report penalty in the hit screen. It's not present when user has Disparo Preciso.
2-Firing a fired weapon wastes 1 of the selected ammo when the user hits "roll".
3-Character can aim using the general action "Mirar". They use a movement action (we dont care about action economy), this makes it so the self-reported -5 penalty to hit is removed. This also lets other powers that interact with mirar be used in their own ways.
