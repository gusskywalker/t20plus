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



Encantos follows a very similar logic and it's even simpler. Encantos can't even be materials.
Número de Encantos / Aumento no Preço	
1	/ + 18.000	
2	/ +36.000	
3	/ + 72.000	

Enchantments and improvements are two separate systems. An item can have 3 improvements and the user decides to add an enchantment. It''l be the first step, costing 18000.
