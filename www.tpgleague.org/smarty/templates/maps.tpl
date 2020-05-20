
	<h3>{$league_title} Map Downloads</h3>
    {if $map_pack_url}<a href="{$map_pack_url}"><strong>Season {$season_number} Map Pack</strong></a><br><br>{/if}
    
<b>Custom Maps Used This Season:</b><br>
{foreach from=$maps_list item='map'}
{if $map.filename != ''}<a href="{$map.filename}">{$map.map_title}</a><br>{/if}
{foreachelse}
No custom maps with downloads are available for this season.<br>
{/foreach}
<br>

{if $lgname == '/dod6' || $lgname == '/draft' || $lgname == '/euro6v6' || $lgname == '/classic' || $lgname == '/regions' || $lgname == '/tpg2' || $lgname == '/dod3'}
<b>All DOD 1.3 Maps Available For Download:</b><br>
<a href="http://files.tpgleague.org/tpg_arena.zip">tpg_arena</a> (made by BIO*TrooPeR for TPG 3v3)<br>
<a href="http://files.tpgleague.org/tpg2/1.3 Beta Map Pack.exe">1.3 Beta Map Pack</a><br>
<a href="http://files.tpgleague.org/tpg2/3.1 Beta Map Pack.exe">3.1 Beta Map Pack</a><br>
<a href="http://files.tpgleague.org/dod3v3/maps/bones2012.zip">Tournament of Bones Map Pack</a><br>
<a href="http://files.tpgleague.org/dod3v3/maps/blitz_cour.exe">blitz_cour</a> (modified middle flag for use in TPG 3v3)<br>
<a href="http://files.tpgleague.org/dod3v3/maps/blitz_magenta.exe">blitz_magenta</a><br>
<a href="http://files.tpgleague.org/dod3v3/maps/dod_25doors_flags.exe">dod_25doors_flags</a> (modified from dod_25doors for use in TPG 3v3)<br>
<a href="http://files.tpgleague.org/dod3v3/maps/dod_2pillboxes.exe">dod_2pillboxes</a><br>
<a href="http://files.tpgleague.org/tpg2/maps/dod_31_anzio.rar">dod_31_anzio</a><br>
<a href="http://files.tpgleague.org/tpg2/dod_adrenalin4.exe">dod_adrenalin4</a><br>
<a href="http://files.tpgleague.org/dod6v6/maps/dod_aleutian.zip">dod_aleutian</a><br>
<a href="http://files.tpgleague.org/dod3v3/maps/dod_anduze.exe">dod_anduze</a><br>
<a href="http://files.tpgleague.org/tpg2/dod_anzio_b1.exe">dod_anzio_b1</a><br>
<a href="http://files.tpgleague.org/dod3v3/maps/dod_aztec.exe">dod_aztec</a><br>
<a href="http://files.tpgleague.org/dod3v3/maps/dod_beerball_flags.exe">dod_beerball_flags</a> (modified from para_beerball for use in TPG 3v3)<br>
<a href="http://files.tpgleague.org/dod3v3/maps/dod_blitzed_v2.exe">dod_blitzed_v2</a><br>
<a href="http://files.tpgleague.org/dod3v3/maps/dod_brume.exe">dod_brume</a> (modified from original for use in TPG 3v3)<br>
<a href="http://files.tpgleague.org/dod3v3/maps/dod_cabinfever.exe">dod_cabinfever</a> (modified mid cap number for use in TPG 3v3)<br>
<a href="http://files.tpgleague.org/dod6v6/maps/dod_caen_13b.zip">dod_caen_13b</a><br>
<a href="http://files.tpgleague.org/tpg2/maps/dod_caen2.zip">dod_caen2</a><br>
<a href="http://files.tpgleague.org/dod6v6/maps/dod_cal_sherman2.zip">dod_cal_sherman2</a><br>
<a href="http://files.tpgleague.org/dod3v3/maps/dod_carta_clan2.exe">dod_carta_clan2</a><br>
<a href="http://files.tpgleague.org/dod3v3/maps/dod_chickenrun.exe">dod_chickenrun</a><br>
<a href="http://files.tpgleague.org/tpg2/maps/dod_cr44_b3.zip">dod_cr44_b3</a><br>
<a href="http://files.tpgleague.org/dod3v3/maps/dod_dani_b3.exe">dod_dani_b3</a><br>
<a href="http://files.tpgleague.org/dod3v3/maps/dod_deckung.exe">dod_deckung</a><br>
<a href="http://files.tpgleague.org/dod3v3/maps/dod_diversion.exe">dod_diversion</a><br>
<a href="http://files.tpgleague.org/dod3v3/maps/dod_dsp.exe">dod_dsp</a><br>
<a href="http://files.tpgleague.org/dod6v6/maps/dod_emannuel_b2.zip">dod_emannuel_b2</a><br>
<a href="http://files.tpgleague.org/dod3v3/maps/dod_fc.exe">dod_fc</a><br>
<a href="http://files.tpgleague.org/tpg2/dod_frenzy.exe">dod_frenzy</a><br>
<a href="http://files.tpgleague.org/dod6v6/maps/dod_harrington.zip">dod_harrington</a><br>
<a href="http://files.tpgleague.org/dod3v3/maps/dod_haven.exe">dod_haven</a> (modified mid cap number for use in TPG 3v3)<br>
<a href="http://files.tpgleague.org/tpg2/dod_hc1.rar">dod_hc1</a><br>
<a href="http://files.tpgleague.org/dod3v3/maps/dod_hostile.exe">dod_hostile</a><br>
<a href="http://files.tpgleague.org/dod3v3/maps/dod_kalt_3v3_b1.exe">dod_kalt_3v3_b1</a> (modified from dod_kalt for use in TPG)<br>
<a href="http://files.tpgleague.org/dod6v6/maps/dod_lennon_b2.zip">dod_lennon_b2</a><br>
<a href="http://files.tpgleague.org/dod6v6/maps/dod_lennon_b3.zip">dod_lennon_b3</a><br>
<a href="http://files.tpgleague.org/dod6v6/maps/dod_lindbergh_b1.zip">dod_lindbergh_b1</a><br>
<a href="http://files.tpgleague.org/dod3v3/maps/dod_mainz.exe">dod_mainz</a><br>
<a href="http://files.tpgleague.org/dod3v3/maps/dod_mort.exe">dod_mort</a> (modified from original for use in TPG 3v3)<br>
<a href="http://files.tpgleague.org/dod6v6/maps/dod_muhle_b2.zip">dod_muhle_b2</a><br>
<a href="http://files.tpgleague.org/dod3v3/maps/dod_murky_halloween.exe">dod_murky_halloween</a> (modified from dod_murky2 by c-ton for TPG 3v3)<br>
<a href="http://files.tpgleague.org/dod3v3/maps/dod_murky2.exe">dod_murky2</a><br>
<a href="http://files.tpgleague.org/dod3v3/maps/dod_neutrino.exe">dod_neutrino</a><br>
<a href="http://files.tpgleague.org/dod3v3/maps/dod_orange.exe">dod_orange</a><br>
<a href="http://files.tpgleague.org/dod6v6/maps/dod_railroad.zip">dod_railroad</a><br>
<a href="http://files.tpgleague.org/dod6v6/maps/dod_railroad2_b2.zip">dod_railroad2_b2</a><br>
<a href="http://files.tpgleague.org/dod3v3/maps/dod_rennes.zip">dod_rennes</a><br>
<a href="http://files.tpgleague.org/dod3v3/maps/dod_rennes_b2.exe">dod_rennes_b2</a> (modified mid cap number for use in TPG 3v3)<br>
<a href="http://files.tpgleague.org/dod3v3/maps/dod_rital.exe">dod_rital</a> (spawns flip after cap)<br>
<a href="http://files.tpgleague.org/dod6v6/maps/dod_sherman2_b5.zip">dod_sherman2_b5</a><br>
<a href="http://files.tpgleague.org/dod6v6/maps/dod_sherman2_b6.zip">dod_sherman2_b6</a><br>
<a href="http://files.tpgleague.org/dod3v3/maps/dod_slope.exe">dod_slope</a><br>
<a href="http://files.tpgleague.org/dod3v3/maps/dod_smalltown.exe">dod_smalltown</a><br>
<a href="http://files.tpgleague.org/dod6v6/maps/dod_snowcity_v3.zip">dod_snowcity_v3</a><br>
<a href="http://files.tpgleague.org/dod3v3/maps/dod_snowpillars_b1.exe">dod_snowpillars_b1</a><br>
<a href="http://files.tpgleague.org/dod3v3/maps/dod_snowtown.exe">dod_snowtown</a> (modified from original for use in TPG 3v3)<br>
<a href="http://files.tpgleague.org/dod6v6/maps/dod_solitude_b2.zip">dod_solitude_b2</a><br>
<a href="http://files.tpgleague.org/dod3v3/maps/dod_square2.exe">dod_square2</a> (modified from original for use in TPG 3v3)<br>
<a href="http://files.tpgleague.org/dod3v3/maps/dod_street_b1.exe">dod_street_b1</a> (several bugs in map)<br>
<a href="http://files.tpgleague.org/dod3v3/maps/dod_strom_b3.exe">dod_strom_b3</a><br>
<a href="http://files.tpgleague.org/dod3v3/maps/dod_ta_m@z3_b2.rar">dod_ta_m@z3_b2</a><br>
<a href="http://files.tpgleague.org/dod3v3/maps/dod_tensions.exe">dod_tensions</a> (modified from para_tensions for use in TPG 3v3)<br>
<a href="http://files.tpgleague.org/dod6v6/maps/dod_thunder.zip">dod_thunder</a><br>
<a href="http://files.tpgleague.org/dod3v3/maps/dod_tiger2_b2.exe">dod_tiger2_b2</a><br>
<a href="http://files.tpgleague.org/tpg2/dod_verdun_final.exe">dod_verdun_final</a><br>
<a href="http://files.tpgleague.org/dod3v3/maps/dod_vire.exe">dod_vire</a><br>
<a href="http://files.tpgleague.org/tpg2/dod_volonne.exe">dod_volonne</a><br>
<a href="http://files.tpgleague.org/dod3v3/maps/dod_waltzb3.exe">dod_waltzb3</a><br>
<a href="http://files.tpgleague.org/dod3v3/maps/dod_xmas_tree.exe">dod_xmas_tree</a><br>
<a href="http://files.tpgleague.org/tpg2/maps/dod_zafod.zip">dod_zafod</a><br>
<a href="http://files.tpgleague.org/dod3v3/maps/mspacman-mappack.exe">mspacman-mappack</a><br>
<a href="http://files.tpgleague.org/dod3v3/maps/ship_fight.exe">ship_fight</a> (does not include custom flag models)<br>
{/if}
<br/><br/><br/>