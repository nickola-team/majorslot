<div data-v-525011a5="" id="main_content" class="content column">
    <div data-v-525011a5="" class="layout-spacing column">
        <div data-v-fad191c6="" data-v-525011a5="" class="main column">
            @if( $detect->isMobile() || $detect->isTablet() ) 
                @include('frontend.dove.layouts.partials.m.banner')
            @else
                @include('frontend.dove.layouts.partials.banner')
            @endif  
            <div data-v-fad191c6="" class="top-games column" style="padding-left: 10px; padding-right: 10px;">
                <div data-v-fad191c6="" class="title row" style="flex-direction: row; margin-bottom: 0px;">
                    <span data-v-fad191c6="" class="text">
                        <img data-v-fad191c6="" src="/frontend/dove/assets/img/icon-6.d1b0eb3.svg" style="margin-right: 5px;">LIVE CASINO
                    </span> 
                    <div data-v-fad191c6="" class="spacer"></div> 
                    <div data-v-fad191c6="" class="carousel row" style="flex-direction: row;">
                        <div data-v-fad191c6="" class="left row" style="flex-direction: row;">
                            <button data-v-fad191c6="" class="button" onclick="scrolLeft('live_list');">
                                <i data-v-e56d064c="" data-v-fad191c6="" class="fa-regular fa-angle-left fa-lg"></i>
                            </button>
                        </div> 
                        <div data-v-fad191c6="" class="right row" style="flex-direction: row;">
                            <button data-v-fad191c6="" class="button" onclick="scrolRight('live_list');">
                                <i data-v-e56d064c="" data-v-fad191c6="" class="fa-regular fa-angle-right fa-lg"></i>
                            </button>
                        </div> 
                        <div data-v-fad191c6="" class="all row" style="flex-direction: row;">
                            <a data-v-fad191c6="" href="#" class=""  onclick="showContent('live_content');">
                                <span data-v-fad191c6="" class="text">ALL</span>
                            </a>
                        </div>
                    </div>
                </div> 
                <div data-v-fad191c6="" class="contents row" style="flex-direction: row;">
                    <div data-v-fad191c6="" class="scroll-menu row" style="flex-direction: row;">
                        <div data-v-05849275="" data-v-fad191c6="" id="live_list" class="list scrollable-auto events row" style="flex-direction: row;">
                        @foreach($categories AS $index=>$category)
                            @if ($category->type =='live')
                                <div data-v-fad191c6="" class="top-game-event zoomIn column">
                                @auth
                                    @if ($category->status == 0)
                                    <button data-v-fad191c6="" class="zoomIn button" onclick="swal2('점검중입니다.', 'warning')">
                                    @else
                                    <button data-v-fad191c6="" class="zoomIn button" onclick="casinoGameStart('{{$category->href}}')">
                                    @endif
                                @else
                                    <button data-v-fad191c6="" class="zoomIn button" onclick="swal2('로그인후 이용가능합니다.', 'error')">
                                @endif
                                        <img data-v-fad191c6="" src="/frontend/dove/assets/img/category/{{strtoupper($category->title)}}.png" class="list-6">
                                    </button>
                                </div>
                            @endif
                        @endforeach
                        </div>
                    </div>
                </div>
            </div> 
            <div data-v-fad191c6="" class="top-games column" style="padding-left: 10px; padding-right: 10px;">
                <div data-v-fad191c6="" class="title row" style="flex-direction: row; margin-bottom: 0px;">
                    <span data-v-fad191c6="" class="text">
                        <img data-v-fad191c6="" src="/frontend/dove/assets/img/icon-4.44b9a13.svg" style="margin-right: 5px;">SLOT GAMES
                    </span> 
                    <div data-v-fad191c6="" class="spacer"></div> 
                    <div data-v-fad191c6="" class="carousel row" style="flex-direction: row;">
                        <div data-v-fad191c6="" class="left row" style="flex-direction: row;">
                            <button data-v-fad191c6="" class="button" onclick="scrolLeft('slot_list');">
                                <i data-v-e56d064c="" data-v-fad191c6="" class="fa-regular fa-angle-left fa-lg"></i>
                            </button>
                        </div> 
                        <div data-v-fad191c6="" class="right row" style="flex-direction: row;">
                            <button data-v-fad191c6="" class="button" onclick="scrolRight('slot_list');">
                                <i data-v-e56d064c="" data-v-fad191c6="" class="fa-regular fa-angle-right fa-lg"></i>
                            </button>
                        </div> 
                        <div data-v-fad191c6="" class="all row" style="flex-direction: row;">
                            <a data-v-fad191c6="" href="#" class=""  onclick="showContent('slot_content');">
                                <span data-v-fad191c6="" class="text">ALL</span>
                            </a>
                        </div>
                    </div>
                </div> 
                <div data-v-fad191c6="" class="contents row" style="flex-direction: row;">
                    <div data-v-fad191c6="" class="scroll-menu row" style="flex-direction: row;">
                        <div data-v-05849275="" data-v-fad191c6="" id="slot_list" class="list scrollable-auto events row" style="flex-direction: row;">
                            @foreach($categories AS $index=>$category)
                                @if ($category->type =='slot')
                                    <div data-v-fad191c6="" class="top-game-event zoomIn column">
                                    @auth
                                        @if ($category->status == 0)
                                        <button data-v-fad191c6="" class="zoomIn button" onclick="swal2('점검중입니다.', 'warning')">
                                        @else
                                        <button data-v-fad191c6="" class="zoomIn button" onclick="slotGame('{{$category->href}}','{{$category->trans?$category->trans->trans_title:$category->title}}')">
                                        @endif
                                    @else
                                        <button data-v-fad191c6="" class="zoomIn button" onclick="swal2('로그인후 이용가능합니다.', 'error')">
                                    @endif
                                            <img data-v-fad191c6="" src="/frontend/dove/assets/img/category/{{strtoupper($category->title)}}.png" class="list-6">
                                        </button>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div> 
            <div data-v-fad191c6="" class="providers column" style="padding-left: 10px; padding-right: 10px;">
                <div data-v-fad191c6="" class="title row" style="flex-direction: row; margin-bottom: 0px;">
                    <span data-v-fad191c6="" class="text">
                        <img data-v-fad191c6="" src="/frontend/dove/assets/img/icon-2.3860d55.svg" style="margin-right: 5px;">GAME PROVIDERS
                    </span> 
                    <div data-v-fad191c6="" class="spacer"></div> 
                    <div data-v-fad191c6="" class="carousel row" style="flex-direction: row;">
                        <div data-v-fad191c6="" class="left row" style="flex-direction: row;">
                            <button data-v-fad191c6="" class="button" onclick="scrolLeft('providers_list');">
                                <i data-v-e56d064c="" data-v-fad191c6="" class="fa-regular fa-angle-left fa-lg"></i>
                            </button>
                        </div> 
                        <div data-v-fad191c6="" class="right row" style="flex-direction: row;">
                            <button data-v-fad191c6="" class="button" onclick="scrolRight('providers_list');">
                                <i data-v-e56d064c="" data-v-fad191c6="" class="fa-regular fa-angle-right fa-lg"></i>
                            </button>
                        </div>
                    </div>
                </div> 
                <div data-v-fad191c6="" class="contents row" style="flex-direction: row;">
                    <div data-v-fad191c6="" class="scroll-menu row" style="flex-direction: row;">
                        <div data-v-05849275="" data-v-fad191c6="" id="providers_list" class="list scrollable-auto events row" style="flex-direction: row;">
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/b39829da268b3eac1b82015facc8d5c2e7f2a495.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/640f0a11a72d3c28bea0df1bfe044436587e282f.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/358b3c510cf250e039c6add9af77b79bf7cbc2d8.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/d72b5f0f415b0b88d60ff7637ad7692d0d23df20.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/fb9b2be380eecef4721e50bfe2b5826d6f68718d.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/42f0cb5da6c6f92ed6c5686575617a3863baf80d.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/2b1de97b5dc1f513a16c28008e8eea9a89d0cc5c.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/376f24d14952ef7c7c383e98c54bd2ac376f206f.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/294b9b0a10e625b518dcda53882afc9bbcb3d683.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/2d5572db7112093051e461a5d8f8f1a993884f1b.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/9ba12eb1e863a7e1de4ac50ad04b40e387597514.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/958ce55d034ceee6a14789c232e4d02ccb47aff3.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/72be07e7b2601c9a6a2a4cee091d5686172fabf9.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/88f62399a0e2768c15949d725ec9616d2a720493.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/d26548439fccb4e31c4527164e896efee01fd684.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/afc3012974867daee2043cc59d7604b45acd764e.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/745395a6898487ea293cf4d64d4304882b07b32b.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/3ad687bb5eb00068c015abcdfbb030cbce6a345b.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/01086973436544f79d59518fea497edc560c656a.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/74184ad01e0aec8cad5718f5e2416e96b2cff02f.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/6857c00adbf91d145f069984d9972b7bab1d222a.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/40f6a345b6326bb50a1ee94e73613317a94a0b6f.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/9cc9e3b4de28e01d3acc513946344c38c20107a6.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/52ac9c946072c41f03fe832be604a80104b19084.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/9e7a101a4a2c6de50ff0050fa97ea531008d4d40.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/544020623e2f1385a06404699b74554616b08ec9.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/28bc3011d414fc8e6fdacda10e1ca112cccec213.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/824060a24408afebbacc053024f511fb659ca813.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/e0b3a573246cfc45ab6622f190fdffdeed8c51bf.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/78ce6b6f8db9f775fb6ac94af9a1ea9fb6937756.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/d565aade3a1ee116326cb258ac2e563315558ad9.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/c9e13b982e224d080640aa52e85de93218d0bab8.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/8809f7fe1d47da52e8f9d1ba8be68a9c6a88df88.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/3c3d6d674d9194ecc56cbcd555706b291d1173e5.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/ac119d5c567069751eebe5ff9fddbbfd8600a112.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/977440bb04a6e0d0f5d73b5e113224d4f7e81a65.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/cd64ed950b430edd497aba77f1b4409e2b0c3065.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/a057d1882e62aa93faf80775590d8cb483b5e37b.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/9381ef43cec87db55418b051e11cd1ead4c6bc5d.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/325da9e7f09c9a88fad368993cccd00746672322.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/4a3e8211a85fea4871f697c12f846282f6d0c122.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/3a8e3b8f966081c37bd13f8926166d4ee699dc22.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/2a9a4462ef5e581355ff6d7bd2b3e5976d504e4b.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/afc337d1abfd80f34677f5403930ab5b37cf3613.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/4036f0925a790391a531ea56efa3dd18622d8422.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/511072499199e958a819fd9cf2cea8d18bfefe6b.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/2792e0b1ffb2cb4ef01df2895ad972354efa6798.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/b4e0dc4e47e9b32e2b650830eb4b6f8fdb9ffd15.png" class="list-6">
                                </button>
                            </div>
                            <div data-v-fad191c6="" class="top-provider-list zoomIn column">
                                <button data-v-fad191c6="" class="button">
                                    <img data-v-fad191c6="" src="https://imghour.com/img/d0d41595069e0b06451c05b0f00f24dbb1330fb1.png" class="list-6">
                                </button>
                            </div> <!---->
                        </div>
                    </div>
                </div>
            </div> 
            <div data-v-fad191c6="" class="latest-bets column" style="padding-left: 10px; padding-right: 10px;">
                <div data-v-fad191c6="" class="title row" style="flex-direction: row;">
                    <span data-v-fad191c6="" class="text">
                        <img data-v-fad191c6="" src="/frontend/dove/assets/img/icon-3.5602481.svg" style="margin-right: 5px; width: 19px;">LATEST BETS
                    </span>
                </div> 
                <div data-v-fad191c6="" class="contents column">
                    <div data-v-fad191c6="" class="list-header row" style="flex-direction: row;">
                        <div data-v-fad191c6="" class="row" style="flex-direction: row;">
                            <span data-v-fad191c6="" class="text">게임</span>
                        </div> 
                        <div data-v-fad191c6="" class="row" style="flex-direction: row;">
                            <span data-v-fad191c6="" class="text">닉네임</span>
                        </div> 
                        <div data-v-fad191c6="" class="row" style="flex-direction: row;">
                            <span data-v-fad191c6="" class="text">시간</span>
                        </div> 
                        <div data-v-fad191c6="" class="row" style="flex-direction: row;">
                            <span data-v-fad191c6="" class="text">베팅금액</span>
                        </div> 
                        <div data-v-fad191c6="" class="row" style="flex-direction: row;">
                            <span data-v-fad191c6="" class="text">배당</span>
                        </div> 
                        <div data-v-fad191c6="" class="row" style="flex-direction: row;">
                            <span data-v-fad191c6="" class="text">당첨금액</span>
                        </div>
                    </div> 
                    <div data-v-fad191c6="" class="fill-height">
                        <div data-v-fad191c6="" class="lists column" id="last_bet_list">
                            
                        </div>
                    </div>
                </div>
            </div> 
            @include('frontend.dove.layouts.partials.footer')
        </div>
    </div>
</div>