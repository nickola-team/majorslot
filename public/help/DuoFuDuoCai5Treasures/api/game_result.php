<!DOCTYPE html>
<html lang="en">

<head>
      <title>Game Record</title>
      <!--Import Google Icon Font-->
      <link type="text/css" rel="stylesheet" href="lib/Classes/materialize/css/material-icons.css" />
      <!--Import materialize.css-->
      <link type="text/css" rel="stylesheet" href="lib/Classes/materialize/css/materialize.cus.min.css" />
      <!--Import bootstrap.css-->
      <link rel="stylesheet" href="lib/Classes/bootstrap/4.2/css/bootstrap.min.css">
      <link rel="stylesheet" href="lib/base.css">
      <link rel="stylesheet" href="lib/Classes/datetimepicker/build/css/bootstrap-datetimepicker.min.css">
      <link rel="stylesheet" href="lib/Classes/fontawesome/css/fa-svg-with-js.css">
      <!--Import bootstrap datepicker.css-->
      <!-- <link rel="stylesheet" href="lib/Classes/bootstrap-4.0.0-dist/datetimepicker/build/css/bootstrap-datetimepicker.min.css"> -->

      <!--Let browser know website is optimized for mobile-->
      <meta name="viewport" content="width=device-width, initial-scale=0.3" />
      <meta charset="utf-8">
</head>

<body>
      <!--Import jQuery before materialize.js-->
      <script type="text/javascript" src="lib/Classes/Jquery/js/jquery-3.2.1.min.js"></script>
      <script type="text/javascript" src="lib/Classes/materialize/js/materialize.min.js"></script>
      <!-- <script src="lib/Classes/bootstrap/4.2/js/popper.1.12.9.min.js"></script> -->
      <script src="lib/Classes/bootstrap/4.2/js/bootstrap.min.js"></script>
      <!--Loading detail result with JQuery ajax-->
      <script src="lib/Classes/customize/game_result.js"></script>
      <!--Import monvemt before datetimepicker-->
      <script src="lib/Classes/monvent/monvent_locale.min.js"></script>
      <!--Import datepicker for datetime query-->
      <script src="lib/Classes/datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
      <script src="lib/Classes/fontawesome/js/fontawesome-all.min.js"></script>
      <!--Init dtPicker-->
      <script src="lib/Classes/customize/detail_of_game_record.js"></script>
</body>

</html>
<div style="display:none">
      <form action='game_result.php' method='get' class='container-fluid flow-text'><br>
            <div class='form-group justify-content-md-center row'><label for='game'
                        class='flow-text teal-text text-lighten-2'>SerialNumber</label>
                  <div class='col-sm-2'><input type='text' class='form-control form-control-sm' name='sn' id='sn'></div>
                  <div><input type='hidden' name='player' id='player' value=''></div><label for='game'
                        class='flow-text teal-text text-lighten-2'>Game</label>
                  <div class='col-sm-4'><select type='text' class='form-control form-control-sm' name='game' id='game'>
                              <option value='' selected='selected'>Select</option>
                              <option value='Slot'>Slot</option>
                              <option value='LittleMarrio'>Little Marrio</option>
                              <option value='BigTwo'>Big Two</option>
                              <option value='ChinesePoker'>ChinesePoker</option>
                              <option value='SlotJumanji'>Brutal Jungle</option>
                              <option value='WaterMargin'>108 Heroes</option>
                              <option value='Mojin'>Mojin</option>
                              <option value='LionDance'>Lion Dance</option>
                              <option value='Baccarat8T'>Baccarat 8T</option>
                              <option value='FIFA2018'>2018 World Cup</option>
                              <option value='Slot2'>Chinese New Year</option>
                              <option value='Mojin2'>Tomb</option>
                              <option value='LionDance2'>Lion Dance</option>
                              <option value='Tropics'>Tropical Island</option>
                              <option value='BattleGrounds'>PUBG</option>
                              <option value='Ninja'>Ninja Warrior</option>
                              <option value='IceWind'>Ice Storm</option>
                              <option value='Odin'>Odin</option>
                              <option value='AliceAdv'>Alice</option>
                              <option value='MagicCandy'>Magic Candy</option>
                              <option value='BeanStalk'>Bean Stalk</option>
                              <option value='Aladin'>Aladdin</option>
                              <option value='ZombieHunter'>Zombie Hunter</option>
                              <option value='EgyptCity'>Lost in Egypt</option>
                              <option value='CrossyRoad'>Crossy Road</option>
                              <option value='FallingSakura'>Falling Sakura</option>
                              <option value='ArtificialGirl'>Artificial Girl</option>
                              <option value='FoxSpirit'>Fox Spirit</option>
                              <option value='PixelWar'>Bright & Abyss</option>
                              <option value='Horizon'>Horizon</option>
                              <option value='MightMagic'>Might Magic</option>
                              <option value='Geisha'>Geisha</option>
                              <option value='Military'>Military</option>
                              <option value='Pirates'>Pirates</option>
                              <option value='MarvelTsum'>Marvel Tsum Tsum</option>
                              <option value='TravelFrog'>Travel Frog</option>
                              <option value='TaikoDrumMaster'>Taiko Master</option>
                              <option value='Onmologist'>Onmyoji</option>
                              <option value='MapleStory'>Maple Story</option>
                              <option value='AzurLane'>WarShip Girls</option>
                              <option value='WarHammer40K'>War Hammer 40K</option>
                              <option value='SpecialChef'>Special Chef</option>
                              <option value='MarvelClassic'>Infinity War</option>
                              <option value='BreakAway'>Speed Racing</option>
                              <option value='DuoFuDuoCai88Fortune'>1c dafudacai-88Fortunes</option>
                              <option value='DuoFuDuoCai5Treasures'>1c dafudacai-5Treasures</option>
                              <option value='DuoFuDuoCaiDancingDrum'>1c dafudacai-DancingDrum</option>
                              <option value='DuoFuDuoCaiDiamondEternity'>1c dafudacai-DiamondEternity</option>
                              <option value='DuoFuDuoCaiFlowerOfRiches'>1c dafudacai-FlowerOfRiches</option>
                              <option value='DuoFuDuoCai'>1c dafudacai</option>
                              <option value='FIFAPlusWaterMargin'>FIFA Plus Water Margin</option>
                              <option value='GreatBlue'>Great Blue</option>
                              <option value='ZhaoCaiTongZi'>Zhao Cai Tong Zi</option>
                              <option value='ZhaoCaiJinBao'>Zhao Cai Jin Bao</option>
                              <option value='BuffaloBlitz'>Buffalo Bonus</option>
                              <option value='BarBarSheep'>Cho Cho Black Sheep</option>
                              <option value='LuckyTwins'>Lucky Twins</option>
                              <option value='FishParty'>Fish Party</option>
                              <option value='KingsOfCash'>Queen Of Cash</option>
                              <option value='PurePlatinum'>Platinum</option>
                              <option value='SunQuest'>Sun Quest</option>
                              <option value='TrueZhaoCaiJinBao'>Zhao Cai Jin Bao 2</option>
                              <option value='ForestParty'>Forest Party</option>
                              <option value='MysticNine'>Mystic Nine</option>
                              <option value='FishingExpert'>Fishing Expert</option>
                              <option value='Arcadia'>Arcadia</option>
                              <option value='PokerCity'>Jo ma ji</option>
                              <option value='SixLions'>Six Lions</option>
                              <option value='MoneyFarm'>Money Farm</option>
                              <option value='FiveFlowers'>5 Dealers</option>
                              <option value='FiveDragons'>5 Dragons</option>
                              <option value='FortuneKing'>Good Fortune</option>
                              <option value='FiveChildTeasingMaitreya'>Wu Zi Ci Mile</option>
                              <option value='LongLongLong'>Long Long Long</option>
                              <option value='LongLongLong2'>Long Long Long 2</option>
                              <option value='Triple7'>777</option>
                              <option value='Chaoji8'>Chaoji 8</option>
                              <option value='FaFaFa'>Fa Fa Fa</option>
                              <option value='Crazy7'>Crazy 7</option>
                              <option value='TripleMonkey'>Triple Monkey</option>
                              <option value='FunkyMonkey'>Funky Monkey</option>
                              <option value='Mahjong'>Mahjong</option>
                              <option value='FiftyLions'>50 Lions</option>
                              <option value='MillionGoldWheel'>Million Gold Wheel</option>
                              <option value='FortuneDragon'>Fortune Dragon</option>
                              <option value='GoldRhinoceros'>Gold Rhinoceros</option>
                              <option value='FiveFrog'>Five Frog</option>
                              <option value='VT'>City Of Poli</option>
                              <option value='WuLuCaiShen'>Wu Lu Cai Shen</option>
                              <option value='LionAltAladdin'>Lion Alt Aladdin</option>
                              <option value='SevenLuckyGod'>Seven Lucky God</option>
                              <option value='TaiWangSiShen'>Tai Wang Si Shen</option>
                              <option value='DiamondSlot'>Diamond Slot</option>
                              <option value='HungryHungryShark'>Hungry Hungry Shark</option>
                              <option value='AsTheGodsWill'>As The Gods Will</option>
                              <option value='WuFuTongZi'>Dragon Skies</option>
                              <option value='DuoFu10c88Fortune'>10c dafudacai-88Fortunes</option>
                              <option value='DuoFu10c5Treasures'>10c dafudacai-5Treasures</option>
                              <option value='DuoFu10cDancingDrum'>10c dafudacai-DancingDrum</option>
                              <option value='DuoFu10cDiamondEternity'>10c dafudacai-DiamondEternity</option>
                              <option value='DuoFu10cFlowerOfRiches'>10c dafudacai-FlowerOfRiches</option>
                              <option value='DuoFuDuoCai10c'>10c dafudacai</option>
                              <option value='DuoFu1d88Fortune'>1d dafudacai-88Fortunes</option>
                              <option value='DuoFu1d5Treasures'>1d dafudacai-5Treasures</option>
                              <option value='DuoFu1dDancingDrum'>1d dafudacai-DancingDrum</option>
                              <option value='DuoFu1dDiamondEternity'>1d dafudacai-DiamondEternity</option>
                              <option value='DuoFu1dFlowerOfRiches'>1d dafudacai-FlowerOfRiches</option>
                              <option value='DuoFuDuoCai1d'>1d dafudacai</option>
                              <option value='DuoFu10d88Fortune'>10d dafudacai-88Fortunes</option>
                              <option value='DuoFu10d5Treasures'>10d dafudacai-5Treasures</option>
                              <option value='DuoFu10dDancingDrum'>10d dafudacai-DancingDrum</option>
                              <option value='DuoFu10dDiamondEternity'>10d dafudacai-DiamondEternity</option>
                              <option value='DuoFu10dFlowerOfRiches'>10d dafudacai-FlowerOfRiches</option>
                              <option value='DuoFuDuoCai10d'>10d dafudacai</option>
                              <option value='LionAltFishParty'>Lion Alt Fish Party</option>
                              <option value='LionAltFiveFlowers'>Lion Alt Five Flowers</option>
                              <option value='LineBrownDaydream'>Get Rich</option>
                              <option value='LionAltNinja'>Lion Alt Ninja</option>
                              <option value='LionAltGeisha'>Lion Alt Geisha</option>
                              <option value='JuFuGoldenTale'>1c JuFuNaCai-Golden Tale</option>
                              <option value='JuFuEternalDiamond'>1c JuFuNaCai-Eternal Diamond</option>
                              <option value='JuFuFountainOfWealth'>1c JuFuNaCai-Fountain Of Wealth</option>
                              <option value='JuFuBloomingRiches'>1c JuFuNaCai-Blooming Riches</option>
                              <option value='JuFuRhythmOfFortune'>1c JuFuNaCai-Rhythm Of Fortune</option>
                              <option value='JuFuNaCai'>1c JuFuNaCai</option>
                              <option value='JuFu10cGoldenTale'>10c JuFuNaCai-Golden Tale</option>
                              <option value='JuFu10cEternalDiamond'>10c JuFuNaCai-Eternal Diamond</option>
                              <option value='JuFu10cFountainOfWealth'>10c JuFuNaCai-Fountain Of Wealth</option>
                              <option value='JuFu10cBloomingRiches'>10c JuFuNaCai-Blooming Riches</option>
                              <option value='JuFu10cRhythmOfFortune'>10c JuFuNaCai-Rhythm Of Fortune</option>
                              <option value='JuFuNaCai10c'>10c JuFuNaCai</option>
                              <option value='JuFu1dGoldenTale'>1d JuFuNaCai-Golden Tale</option>
                              <option value='JuFu1dEternalDiamond'>1d JuFuNaCai-Eternal Diamond</option>
                              <option value='JuFu1dFountainOfWealth'>1d JuFuNaCai-Fountain Of Wealth</option>
                              <option value='JuFu1dBloomingRiches'>1d JuFuNaCai-Blooming Riches</option>
                              <option value='JuFu1dRhythmOfFortune'>1d JuFuNaCai-Rhythm Of Fortune</option>
                              <option value='JuFuNaCai1d'>1d JuFuNaCai</option>
                              <option value='JuFu10dGoldenTale'>10d JuFuNaCai-Golden Tale</option>
                              <option value='JuFu10dEternalDiamond'>10d JuFuNaCai-Eternal Diamond</option>
                              <option value='JuFu10dFountainOfWealth'>10d JuFuNaCai-Fountain Of Wealth</option>
                              <option value='JuFu10dBloomingRiches'>10d JuFuNaCai-Blooming Riches</option>
                              <option value='JuFu10dRhythmOfFortune'>10d JuFuNaCai-Rhythm Of Fortune</option>
                              <option value='JuFuNaCai10d'>10d JuFuNaCai</option>
                              <option value='Tarzan'>Tarzan</option>
                              <option value='MonkeyClimbTree'>Monkey Climb Tree</option>
                              <option value='TrueBreakAway'>BreakAway</option>
                              <option value='JurassicWorld'>Jurassic Park</option>
                              <option value='Circus'>AmazingCircus</option>
                              <option value='Halloween'>Halloween</option>
                              <option value='Rich'>Richman</option>
                              <option value='CandyCrush'>Candy Crush</option>
                              <option value='CandyCarnival'>Candy Carnival</option>
                              <option value='GoldPig'>Gold Pig</option>
                              <option value='DJRemix'>DJ Remix</option>
                              <option value='TripleMonkey10C'>10C Triple Monkey</option>
                              <option value='Geisha2'>Geisha 2</option>
                              <option value='CalabashBrothers'>Calabash Brothers</option>
                              <option value='LostCityOfGold'>Lost City Of Gold</option>
                              <option value='RugbyWorldCup'>Rugby World Cup</option>
                              <option value='PharaohResortHotel'>Pharaoh Resort Hotel</option>
                              <option value='GoldAynu'>Gold Aynu</option>
                              <option value='MysticWitch'>Mystic Witch</option>
                              <option value='DoubleBlessings'>Double Blessings</option>
                              <option value='ThewizardofOZ'>The Wizard of OZ</option>
                              <option value='Aladdin2'>Aladdin</option>
                              <option value='GoldMeowSushiLegend'>Gold Meow Sushi Legend</option>
                              <option value='LuckyPig'>Lucky Pig</option>
                              <option value='ChineseHalloween'>Chinese Halloween</option>
                              <option value='UniverseQuest'>Universe Quest</option>
                              <option value='LuckyTwinsEX'>New Lucky Twins </option>
                              <option value='TripleMonkeyEX'>New Triple Monkey </option>
                              <option value='ChineseNewYear2020'>Chinese New Year 2020</option>
                              <option value='FiveDragonsEX'>New Five Dragons</option>
                              <option value='WuLuCaiShenEX'>New Wu Lu Cai Shen</option>
                              <option value='BuffaloBlitzEX'>New Buffalo Bonus</option>
                              <option value='SevenLuckyGodEX'>New Seven Lucky God</option>
                              <option value='ZhaoCaiTongZiEX'>New Zhao Cai Tong Zi</option>
                              <option value='LongLongLongEX'>New Long Long Long</option>
                              <option value='Triple7EX'>New Triple 7</option>
                              <option value='FaFaFaEX'>New Fa Fa Fa</option>
                              <option value='FunkyMonkeyEX'>New Funky Monkey</option>
                              <option value='TrueZhaoCaiJinBaoEX'>New True Zhao Cai Jin Bao</option>
                              <option value='ZhaoCaiJinBaoEX'>New Zhao Cai Jin Bao</option>
                              <option value='PurePlatinumEX'>New Pure Platinum</option>
                              <option value='ArcadiaEX'>New Arcadia</option>
                              <option value='JurassicWorldEX'>New Jurassic Park</option>
                              <option value='GreatBlueEX'>New Great Blue</option>
                              <option value='DragonSkiesEX'>New Dragon Skies</option>
                              <option value='AfricanLion'>Africa Lion</option>
                              <option value='CircusEX'>New Amazing Circus</option>
                              <option value='TaiWangSiShenEX'>New Tai Wang Si Shen</option>
                              <option value='DiamondSlotEX'>New Diamond Slot</option>
                              <option value='GeishaEX'>New Geisha</option>
                              <option value='WaterMarginEX'>New 108 Heroes</option>
                              <option value='EgyptCityEX'>Scarab</option>
                              <option value='IceWindEX'>New Ice Storm</option>
                              <option value='FiveChildTeasingMaitreyaEX'>New Wu Zi Ci Mile</option>
                              <option value='AladdinEX'>New Aladdin</option>
                              <option value='TarzanEX'>New Tarzan</option>
                              <option value='HorizonEX'>New Horizon</option>
                              <option value='BreakAwayEX'>New Speed Racing</option>
                              <option value='RichEX'>New Richman</option>
                              <option value='Chaoji8EX'>New Chaoji 8</option>
                              <option value='VTEX'>New City Of Poli</option>
                              <option value='AsTheGodsWillEX'>New As The Gods Will</option>
                              <option value='HungryHungrySharkEX'>New Hungry Hungry Shark</option>
                              <option value='PokerCityEX'>New Jomaji</option>
                              <option value='MarvelTsumEX'>New Marvel Tsum Tsum</option>
                              <option value='LineBrownDaydreamEX'>New Get Rich</option>
                              <option value='SpecialChefEX'>New Special Chef</option>
                              <option value='FishingExpertEX'>New Fishing Expert</option>
                              <option value='FishPartyEX'>New Fish Party</option>
                              <option value='KingsOfCashEX'>New Queen Of Cash</option>
                              <option value='SunQuestEX'>Universe Quest</option>
                              <option value='MoneyFarmEX'>Money Farm 3</option>
                              <option value='SixLionsEX'>New Six Lions</option>
                              <option value='BarBarSheepEX'>Black Sheep</option>
                              <option value='TaikoDrumMasterEX'>New Taiko Master</option>
                              <option value='OnmologistEX'>New Onmyoji</option>
                              <option value='PiratesEX'>New Pirates</option>
                              <option value='AzurLaneEX'>New Azur Lane</option>
                              <option value='MarvelClassicEX'>New Infinity War</option>
                              <option value='WarHammer40KEX'>New WarHammer 40000</option>
                              <option value='TravelFrogEX'>New Travel Frog</option>
                              <option value='CrossyRoadEX'>New Crossy Road</option>
                              <option value='ChampionCup2020'>Champion Cup 2020</option>
                              <option value='FallingSakuraEX'>New Falling Sakura </option>
                              <option value='LionDance2EX'>New Lion Dance</option>
                              <option value='PixelWarEX'>New Bright & Abyss</option>
                              <option value='AliceAdvEX'>New Alice</option>
                              <option value='MightMagicEX'>New Might Magic</option>
                              <option value='TropicsEX'>New Tropical Island</option>
                              <option value='OdinEX'>New Odin</option>
                              <option value='NinjaEX'>New Ninja Warrior</option>
                              <option value='DjHime'>Dj Hime</option>
                              <option value='GF401'>Panther Moon 2 </option>
                              <option value='GF402'>Thai Paradise</option>
                              <option value='GF403'>Safari Heat</option>
                              <option value='GF404'>50 Dragons</option>
                              <option value='GF405'>A night out</option>
                              <option value='GF406'>Aladdin pro</option>
                              <option value='GF407'>Alice pro</option>
                              <option value='GF408'>Alice Deluxe</option>
                              <option value='GF409'>Ancient Egypt</option>
                              <option value='GF410'>Archer</option>
                              <option value='GF411'>Arctic Treasure</option>
                              <option value='GF412'>AZTECA</option>
                              <option value='GF413'>Bonus Bears</option>
                              <option value='GF414'>Caishen Riches</option>
                              <option value='GF415'>Captain\’s Treasure</option>
                              <option value='GF416'>Captain\’s Treasure pro</option>
                              <option value='GF417'>Captains Treasure Progressive</option>
                              <option value='GF418'>Chinese Boss</option>
                              <option value='GF419'>Choy Sun Doa</option>
                              <option value='GF420'>Crypto Mania</option>
                              <option value='GF421'>Dolphin Reef</option>
                              <option value='GF422'>Dragon Phoenix</option>
                              <option value='GF423'>Dragon Power Flame</option>
                              <option value='GF424'>Dynamite Reels</option>
                              <option value='GF425'>Five Tiger Generals</option>
                              <option value='GF426'>Egypt Queen</option>
                              <option value='GF427'>Empress Reguant</option>
                              <option value='GF428'>Enter the KTV</option>
                              <option value='GF429'>Five Dragons</option>
                              <option value='GF430'>football</option>
                              <option value='GF431'>Four Dragons</option>
                              <option value='GF432'>Genie</option>
                              <option value='GF433'>Golden Island</option>
                              <option value='GF434'>Golden Monkey King</option>
                              <option value='GF435'>Happy Buddha</option>
                              <option value='GF436'>Highway Kings</option>
                              <option value='GF437'>HUGA2</option>
                              <option value='GF438'>Jungle Island</option>
                              <option value='GF439'>Lions Dance</option>
                              <option value='GF440'>Lucky God</option>
                              <option value='GF441'>Mamma mia</option>
                              <option value='GF442'>Miami</option>
                              <option value='GF443'>MoneyBangBang</option>
                              <option value='GF444'>Mulan</option>
                              <option value='GF445'>Neptune Treasure</option>
                              <option value='GF446'>Ocean Paradise</option>
                              <option value='GF447'>Pan Jin Lian</option>
                              <option value='GF448'>Phoenix 888</option>
                              <option value='GF449'>Robin Hood</option>
                              <option value='GF450'>Roma</option>
                              <option value='GF451'>Santa Surprise</option>
                              <option value='GF452'>Silver Bullet</option>
                              <option value='GF453'>Sparta</option>
                              <option value='GF454'>Sparta2</option>
                              <option value='GF455'>SupermeCaishen</option>
                              <option value='GF456'>Thunder God</option>
                              <option value='GF457'>God\’s seat</option>
                              <option value='GF458'>White Snake</option>
                              <option value='GF459'>Wild Glant PANDA</option>
                              <option value='GF460'>Wild Spirit</option>
                              <option value='GF461'>BeansTalk</option>
                              <option value='GF462'>Monkey King</option>
                              <option value='GF463'>Bushido Blade</option>
                              <option value='BoomBoomPow'>Boom Boom Pow</option>
                              <option value='HatTrick'>Hat Trick</option>
                              <option value='FourWealthCreatures'>Four Wealth Creatures</option>
                              <option value='FlowerofRiches'>Flower of Riches</option>
                              <option value='Treasures5'>5 Treasures</option>
                              <option value='DancingDrums'>DancingDrums</option>
                              <option value='FastballMatch'>Fastball Match</option>
                              <option value='TGNiuNiu'>TG NiuNiu</option>
                              <option value='TGFishShrampCrab'>TG Fish Shramp Crab</option>
                              <option value='TGLuckyWheel'>TG Lucky Wheel</option>
                              <option value='TGSicBo'>TG Sic Bo</option>
                              <option value='CallOfNaughtyRacing'>Call Of Naughty-Racing</option>
                              <option value='KimtheQueen'>Kim the Queen</option>
                              <option value='SushiVeteran'>Sushi Veteran</option>
                              <option value='GuardianRamakien'>Guardian Ramakien</option>
                              <option value='CherryMaster'>Cherry Master</option>
                              <option value='TripleMonkeyLite'>Triple Monkey Lite</option>
                              <option value='DiceOfFishPrawnCrab'>Dice Of Fish Prawn Crab</option>
                              <option value='DiceOfInternational'>Dice Of International</option>
                              <option value='DiceOfLucky'>Dice Of Lucky</option>
                              <option value='DiceOfBonusJackpot'>Dice Of Bonus Jackpot</option>
                              <option value='Baccarat'>Baccarat</option>
                              <option value='Baccarat5T'>Baccarat5 T2</option>
                              <option value='Baccarat6T'>Baccarat6 T2</option>
                              <option value='BaccaratCommissionFree'>Baccarat Commission Free</option>
                              <option value='Wheel'>Wheel</option>
                              <option value='BigWheel'>Big Wheel</option>
                              <option value='RichRunner'>Rich Runner</option>
                              <option value='BorderLobby'>Border Lobby</option>
                              <option value='Baccarat2'>Baccarat 2</option>
                              <option value='Bairenniuniu'>Bairenniuniu</option>
                              <option value='BlackJack'>Black Jack</option>
                              <option value='HooHeyHow'>Hoo Hey How</option>
                              <option value='DragonTiger'>Dragon Tiger</option>
                              <option value='Lucky5'>Lucky5</option>
                              <option value='ShotDragon'>Dragon Game Poker</option>
                              <option value='InternationalSicbo'>International Sicbo</option>
                              <option value='ForestDance'>Forest Party</option>
                              <option value='DeucesWild'>Deuces Wild</option>
                              <option value='DragonPhoenixFHD'>Dragon Phoenix</option>
                              <option value='KingsQueenFHD'>Kings Queen</option>
                              <option value='BenzBMWFHD'>Benz BMW</option>
                              <option value='LittleMarryFHD'>Little Marry</option>
                              <option value='GoldenSharkFHD'>Golden Shark</option>
                              <option value='FowlsBeatsFHD'>Fowls Beats</option>
                              <option value='TwSicbo'>Tw Sicbo</option>
                              <option value='TaiSicboFHD'>Tai Sicbo</option>
                              <option value='LuckySicboFHD'>Lucky Sicbo</option>
                              <option value='DamanguanSicboFHD'>Damanguan Sicbo</option>
                              <option value='WanrenJinhuaFHD'>Wanren Jinhua</option>
                              <option value='FraudJinhuaFHD'>Fraud Jinhua</option>
                              <option value='SpeedJinhuaFHD'>Speed Jinhua</option>
                              <option value='ThreeCardPokerFHD'>Three Card Poker</option>
                              <option value='MonkeyClimbingFHD'>Monkey Climbing</option>
                              <option value='PigRace'>Pig Racing</option>
                              <option value='RacingFHD'>Racing</option>
                              <option value='HorseRace'>Horse Race</option>
                              <option value='DogRace'>Dog Race</option>
                              <option value='RacingBoat'>Racing Boat</option>
                              <option value='RacingCar'>Racing Car</option>
                              <option value='EZBaccaratFHD'>EZ Baccarat</option>
                              <option value='LongSuperBaccaratFHD'>Long Super Baccarat</option>
                              <option value='BaccaratCommissionFree2FHD'>Baccarat Commission Free 2</option>
                              <option value='Baccarat5T2FHD'>Baccarat5 T2</option>
                              <option value='Baccarat6T2FHD'>Baccarat6 T2</option>
                              <option value='SuperBaccarat6TFHD'>Super Baccarat 6T</option>
                              <option value='SuperNewBaccarat6TFHD'>Super New Baccarat 6T</option>
                              <option value='Baccarat7TFHD'>Baccarat 7T</option>
                              <option value='Baccarat8T2FHD'>Baccarat 8T</option>
                              <option value='Lucky5Advanced'>Lucky5 Advanced</option>
                              <option value='Lucky5Superme'>Lucky5 Superme</option>
                              <option value='WheelAdvanced'>Wheel Advanced</option>
                              <option value='WheelSuperme'>Wheel Superme</option>
                              <option value='BigWheelAdvanced'>Big Wheel Advanced</option>
                              <option value='BigWheelSuperme'>Big Wheel Superme</option>
                              <option value='ForestDanceAdvanced'>Forest Dance Advanced</option>
                              <option value='ForestDanceSuperme'>Forest Dance Superme</option>
                              <option value='SuperRoulette'>Super Roulette</option>
                              <option value='LuckyRoulette'>Lucky Roulette</option>
                              <option value='MakccaratNonCommission'>Makccarat Non Commission</option>
                              <option value='MakccaratCommission'>Makccarat Commission</option>
                              <option value='Suibao'>Suibao</option>
                              <option value='SuibaoPK'>Suibao PK</option>
                              <option value='CaribbeanStudPoker'>Caribbean Stud Poker</option>
                              <option value='TwSicboFHD'>Tw Sicbo</option>
                              <option value='TaiSicbo'>Tai Sicbo</option>
                              <option value='BenzBMW'>Benz BMW</option>
                              <option value='GoldenShark'>Golden Shark</option>
                              <option value='JpFishing'>Jp Fishing</option>
                              <option value='YummyHaNoi'>Yummy Ha Noi</option>
                              <option value='PokerMachine'>Poker Machine</option>
                              <option value='IgashiContest'>Igashi Contest</option>
                              <option value='RomanceinVingHaLong'>Romance in Ving Ha Long</option>
                              <option value='GanesasBlessing'>Ganesa's Blessing</option>
                        </select></div><label></label>
                  <div><button name='' value='Submit' type='submit' class='btn btn-primary'>Submit</button></div>
            </div>
            <div class='form-group justify-content-md-center row'><label for='start_time'
                        class='flow-text teal-text text-lighten-2'>Date</label>
                  <div class='col-sm-4 input-group'><input type='text' class='form-control dtpicker' name='start_time'
                              id='input_startTime' placeholder='Start' autocomplete='off' readonly='true'></div>
                  <div class='col-sm-4 input-group'><input type='text' class='form-control dtpicker' name='end_time'
                              id='input_endTime' placeholder='End' autocomplete='off' readonly='true'></div>
                  <div><input type='hidden' name='game_id' id='game_id' value='111407'></div>
                  <div><input type='hidden' name='firm' id='firm' value=''></div>
            </div>
      </form>
</div>
<div class="container-fluid flow-text">
      <main id="main" col-12 col-md-9 col-xl-8 py-md-3 pl-md-5 bd-content" role="main">
            <div class="table-responsive">
                  <table class="table table-hover">
                        <thead class="thead-dark">
                              <tr>
                                    <th scope=col>Total bet</th>
                                    <th scope=col>Total win</th>
                                    <th scope=col>Net income</th>
                              </tr>
                        </thead>
                        <tbody>
                              <tr>
                                    <td>31.68</td>
                                    <td>1.95</td>
                                    <td>-29.73</td>
                              </tr>
                        </tbody>
                  </table>
            </div>
            <div class="table-responsive">
                  <table class="table table-hover">
                        <thead class="thead-dark">
                              <tr>
                                    <th scope=col>지시사항</th>
                                    <th scope=col>GameName</th>
                                    <th scope=col>날짜 (UTC+8)</th>
                                    <th scope=col>배팅</th>
                                    <th scope=col>승</th>
                                    <th scope=col>메인</th>
                                    <th scope=col>보너스</th>
                                    <th scope=col>잭팟</th>
                                    <th scope=col>게임결과</th>
                              </tr>
                        </thead>
                        <tbody>
                              <tr>
                                    <td>27241196</td>
                                    <td>1c dafudacai-5Treasures</td>
                                    <td>2021-01-22 02:24:58</td>
                                    <td>2.64</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td><button type="button" id="btn_637468790987037550_111407"
                                                class="btn btn-detail waves-light" data-toggle="modal"
                                                data-target=#modal_637468790987037550_111407><i
                                                      class="material-icons">view_module</i></button></td>
                              </tr>
                              <tr>
                                    <td>27241192</td>
                                    <td>1c dafudacai-5Treasures</td>
                                    <td>2021-01-22 02:24:55</td>
                                    <td>2.64</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td><button type="button" id="btn_637468790954068867_111407"
                                                class="btn btn-detail waves-light" data-toggle="modal"
                                                data-target=#modal_637468790954068867_111407><i
                                                      class="material-icons">view_module</i></button></td>
                              </tr>
                              <tr>
                                    <td>27241188</td>
                                    <td>1c dafudacai-5Treasures</td>
                                    <td>2021-01-22 02:24:52</td>
                                    <td>2.64</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td><button type="button" id="btn_637468790922662489_111407"
                                                class="btn btn-detail waves-light" data-toggle="modal"
                                                data-target=#modal_637468790922662489_111407><i
                                                      class="material-icons">view_module</i></button></td>
                              </tr>
                              <tr>
                                    <td>27241186</td>
                                    <td>1c dafudacai-5Treasures</td>
                                    <td>2021-01-22 02:24:46</td>
                                    <td>2.64</td>
                                    <td>0.15</td>
                                    <td>0.15</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td><button type="button" id="btn_637468790869693708_111407"
                                                class="btn btn-detail waves-light" data-toggle="modal"
                                                data-target=#modal_637468790869693708_111407><i
                                                      class="material-icons">view_module</i></button></td>
                              </tr>
                              <tr>
                                    <td>27241184</td>
                                    <td>1c dafudacai-5Treasures</td>
                                    <td>2021-01-22 02:24:43</td>
                                    <td>2.64</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td><button type="button" id="btn_637468790837506320_111407"
                                                class="btn btn-detail waves-light" data-toggle="modal"
                                                data-target=#modal_637468790837506320_111407><i
                                                      class="material-icons">view_module</i></button></td>
                              </tr>
                              <tr>
                                    <td>27241175</td>
                                    <td>1c dafudacai-5Treasures</td>
                                    <td>2021-01-22 02:24:40</td>
                                    <td>2.64</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td><button type="button" id="btn_637468790805318624_111407"
                                                class="btn btn-detail waves-light" data-toggle="modal"
                                                data-target=#modal_637468790805318624_111407><i
                                                      class="material-icons">view_module</i></button></td>
                              </tr>
                              <tr>
                                    <td>27241160</td>
                                    <td>1c dafudacai-5Treasures</td>
                                    <td>2021-01-22 02:24:37</td>
                                    <td>2.64</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td><button type="button" id="btn_637468790773912325_111407"
                                                class="btn btn-detail waves-light" data-toggle="modal"
                                                data-target=#modal_637468790773912325_111407><i
                                                      class="material-icons">view_module</i></button></td>
                              </tr>
                              <tr>
                                    <td>27241154</td>
                                    <td>1c dafudacai-5Treasures</td>
                                    <td>2021-01-22 02:24:33</td>
                                    <td>2.64</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td><button type="button" id="btn_637468790736412155_111407"
                                                class="btn btn-detail waves-light" data-toggle="modal"
                                                data-target=#modal_637468790736412155_111407><i
                                                      class="material-icons">view_module</i></button></td>
                              </tr>
                              <tr>
                                    <td>27241153</td>
                                    <td>1c dafudacai-5Treasures</td>
                                    <td>2021-01-22 02:24:27</td>
                                    <td>2.64</td>
                                    <td>1.80</td>
                                    <td>1.80</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td><button type="button" id="btn_637468790677662117_111407"
                                                class="btn btn-detail waves-light" data-toggle="modal"
                                                data-target=#modal_637468790677662117_111407><i
                                                      class="material-icons">view_module</i></button></td>
                              </tr>
                              <tr>
                                    <td>27241152</td>
                                    <td>1c dafudacai-5Treasures</td>
                                    <td>2021-01-22 02:24:24</td>
                                    <td>2.64</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td><button type="button" id="btn_637468790646256023_111407"
                                                class="btn btn-detail waves-light" data-toggle="modal"
                                                data-target=#modal_637468790646256023_111407><i
                                                      class="material-icons">view_module</i></button></td>
                              </tr>
                              <tr>
                                    <td>27241145</td>
                                    <td>1c dafudacai-5Treasures</td>
                                    <td>2021-01-22 02:24:19</td>
                                    <td>2.64</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td><button type="button" id="btn_637468790591724760_111407"
                                                class="btn btn-detail waves-light" data-toggle="modal"
                                                data-target=#modal_637468790591724760_111407><i
                                                      class="material-icons">view_module</i></button></td>
                              </tr>
                              <tr>
                                    <td>27241143</td>
                                    <td>1c dafudacai-5Treasures</td>
                                    <td>2021-01-22 02:24:14</td>
                                    <td>2.64</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td><button type="button" id="btn_637468790540005740_111407"
                                                class="btn btn-detail waves-light" data-toggle="modal"
                                                data-target=#modal_637468790540005740_111407><i
                                                      class="material-icons">view_module</i></button></td>
                              </tr>
                        </tbody>
                  </table>
            </div>
</div>
<nav class="navbar-dark bg-dark" aria-label="Page navigation">
      <ul class="pagination pagination-lg">
            <li class="page-item"><a class="page-link"
                        href="?game_id=111407&lang=ko-kr&gameType=DuoFuDuoCai5Treasures&page=0"
                        aria-label="Previous"><span aria-hidden="true">&laquo;</span><span
                              class="sr-only">Previous</span></a></li>
            <li class="page-item active" href="?game_id=111407&lang=ko-kr&gameType=DuoFuDuoCai5Treasures&page=0"><span
                        class="page-link">1<span class="sr-only">(current)</span></a></li>
            <li class="page-item"><a class="page-link"
                        href="?game_id=111407&lang=ko-kr&gameType=DuoFuDuoCai5Treasures&page=0" aria-label="Next"><span
                              aria-hidden="true">&raquo;</span><span class="sr-only">Next</span></a></li>
      </ul>
</nav>
</div>