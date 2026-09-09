items can have up to 4 improvements (item_improvements table) and up to 3 enchantments (item_enchantments)

the cost for imporvements follows this simple logic:

Número de Melhorias /	Aumento no Preço	
1	/ + 300	
2	/ + 3000	
3	/ + 9000	
4	/ + 18000	

So, if a user chooses two improvements

First one costs 300, second one costs 3000. Totalizing 3300.

Some improvements can be materials (is_material in the item_improvements table)
For calculating cost, this simply adds the material cost to the current improvement step

So, user chooses Maciça (not a material) and Adamante (a material that costs 3000)
step 1 -> + 300
step 2 -> +3000 (from being the second step) +3000 (from adamante)

Final cost: 6300

Small caveat: items can only have one "is_material" improvement. Items cant be made of Adamante AND also Matéria Vermelha, for example.

A material's extra cost can vary by which category it's applied to (e.g. Matéria Vermelha costs differently on a weapon vs. armor vs. esotérico). extra_cost is a `{category: cost}` json object, not a flat number — look up the applied item's own category to get the right figure.



Encantos follows a very similar logic and it's even simpler. Encantos can't even be materials.
Número de Encantos / Aumento no Preço	
1	/ + 18.000	
2	/ +36.000	
3	/ + 72.000	

Enchantments and improvements are two separate systems. An item can have 3 improvements and the user decides to add an enchantment. It''l be the first step, costing 18000.


Quirk: When an improv/enchant has another as a pre-req, the new one overrides the old one. They do not stack. For example, Cruel gives +1 damage, Atroz gives +2 damage. The weapon will have +2 damage, not +3.


This is from the Mestre Armeiro Distinction:
Theres also innovations. They ONLY work for firearms and their respective ammo.
The pricing on them follows the pricing on Melhorias, same deal.
So a firearm can have 4 melhorias, 4 encantamentos and 4 inovações.
So, for firearms, its just another 4 dropdowns in the item-improvements-modal. Those dropdowns only show the inovações. The rest, works exactly like melhorias.
