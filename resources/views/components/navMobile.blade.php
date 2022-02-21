<!-- Off-canvas menu for mobile, show/hide based on off-canvas menu state. -->
<div class="fixed inset-0 flex z-40 md:hidden" role="dialog" aria-modal="true">
    <!--
      Off-canvas menu overlay, show/hide based on off-canvas menu state.

      Entering: "transition-opacity ease-linear duration-300"
        From: "opacity-0"
        To: "opacity-100"
      Leaving: "transition-opacity ease-linear duration-300"
        From: "opacity-100"
        To: "opacity-0"
    -->
    <div class="fixed inset-0 bg-gray-600 bg-opacity-75" aria-hidden="true"></div>

    <!--
      Off-canvas menu, show/hide based on off-canvas menu state.

      Entering: "transition ease-in-out duration-300 transform"
        From: "-translate-x-full"
        To: "translate-x-0"
      Leaving: "transition ease-in-out duration-300 transform"
        From: "translate-x-0"
        To: "-translate-x-full"
    -->
    <div class="relative flex-1 flex flex-col max-w-xs w-full pt-5 pb-4 bg-gray-800">
        <!--
          Close button, show/hide based on off-canvas menu state.

          Entering: "ease-in-out duration-300"
            From: "opacity-0"
            To: "opacity-100"
          Leaving: "ease-in-out duration-300"
            From: "opacity-100"
            To: "opacity-0"
        -->
        <div class="absolute top-0 right-0 -mr-12 pt-2">
            <button class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                <span class="sr-only">Close sidebar</span>
                <!-- Heroicon name: outline/x -->
                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="flex-shrink-0 flex items-center px-4">
            <svg width="100%" height="100%" viewBox="0 0 735 457" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;">
                <g transform="matrix(1,0,0,1,-82.6879,-56.7558)">
                    <g id="Layer-1" serif:id="Layer 1" transform="matrix(4.16667,0,0,4.16667,0,0)">
                        <g transform="matrix(-0.985748,0.168231,0.168231,0.985748,34.6989,62.8459)">
                            <path d="M1.461,-37.654C1.461,-37.654 2.912,-29.3 -0.791,-22.875C-4.708,-16.078 -8.698,-11.523 1.461,0.419C1.461,0.419 6.73,-7.313 9.137,-13.99C13.388,-25.778 9.992,-30.724 1.461,-37.654" style="fill:rgb(241,90,41);fill-rule:nonzero;"/>
                        </g>
                        <g transform="matrix(-0.991782,0.127938,0.127938,0.991782,42.6566,55.0723)">
                            <path d="M2.589,-42.128C2.589,-42.128 2.789,-31.791 -1.563,-25.123C-6.941,-16.882 -6.669,-11.741 2.589,0.181C2.589,0.181 0.119,-6.667 4.066,-12.715C11.051,-23.411 11.881,-30.872 2.589,-42.128" style="fill:rgb(247,147,29);fill-rule:nonzero;"/>
                        </g>
                        <g transform="matrix(1,0,0,1,39.5217,65.0233)">
                            <path d="M0,6.007L0.293,0L-6.689,0.006L-13.67,0L-13.377,6.007L0,6.007Z" style="fill:rgb(27,54,100);fill-rule:nonzero;"/>
                        </g>
                        <g transform="matrix(1,0,0,1,39.4871,122.903)">
                            <path d="M0,-49.894L-13.308,-49.894C-13.308,-49.894 -13.513,-39.275 -19.642,-36.081C-14.641,-24.466 -11.229,-10.511 -9.13,0L-7.548,0L-7.548,-30.524C-8.592,-30.894 -9.344,-31.88 -9.344,-33.05C-9.344,-34.536 -8.14,-35.741 -6.654,-35.741C-5.168,-35.741 -3.964,-34.536 -3.964,-33.05C-3.964,-31.88 -4.716,-30.894 -5.76,-30.524L-5.76,0L-4.178,0C-2.079,-10.511 1.333,-24.466 6.334,-36.081C0.205,-39.275 0,-49.894 0,-49.894" style="fill:rgb(27,54,100);fill-rule:nonzero;"/>
                        </g>
                        <g transform="matrix(1,0,0,1,68.9816,49.3174)">
                            <path d="M0,-15.099L0,-7.23L5.446,-7.23C7.794,-7.23 9.259,-8.977 9.259,-11.174C9.259,-13.54 7.719,-15.099 5.446,-15.099L0,-15.099ZM5.878,-2.028L0,-2.028L0,5.202L-6.179,5.202L-6.179,-20.301L5.878,-20.301C11.381,-20.301 15.587,-16.62 15.587,-11.155C15.587,-5.709 11.381,-2.028 5.878,-2.028" style="fill:rgb(27,54,100);fill-rule:nonzero;"/>
                        </g>
                        <g transform="matrix(1,0,0,1,93.4432,55.0827)">
                            <path d="M0,-18.592L0,-8.376C0,-6.366 1.597,-5.408 3.024,-5.408C4.507,-5.408 6.028,-6.366 6.028,-8.376L6.028,-18.592L11.945,-18.592L11.945,-0.563L6.517,-0.563L6.329,-2.272C5.052,-0.657 2.874,0 0.751,0C-2.648,0 -5.916,-2.385 -5.916,-6.686L-5.916,-18.592L0,-18.592Z" style="fill:rgb(27,54,100);fill-rule:nonzero;"/>
                        </g>
                        <g transform="matrix(1,0,0,1,116.969,44.2842)">
                            <path d="M0,-2.967L0,2.855C0,5.916 2.723,5.653 5.577,4.977L5.991,9.785C4.676,10.273 2.704,10.611 1.202,10.611C-2.93,10.611 -5.916,8.62 -5.916,3.813L-5.916,-2.967L-8.789,-2.967L-8.789,-7.793L-5.916,-7.793L-5.916,-13.578L-0.188,-13.578L-0.188,-7.793L4.902,-7.793L4.902,-2.967L0,-2.967Z" style="fill:rgb(27,54,100);fill-rule:nonzero;"/>
                        </g>
                        <g transform="matrix(1,0,0,1,137.833,36.1155)">
                            <path d="M0,18.404L0,8.376C0,6.366 -1.596,5.408 -3.024,5.408C-4.507,5.408 -6.029,6.366 -6.029,8.376L-6.029,18.404L-11.944,18.404L-11.944,0.375L-6.517,0.375L-6.329,2.272C-5.052,0.657 -2.873,0 -0.752,0C2.648,0 5.915,2.385 5.915,6.685L5.915,18.404L0,18.404Z" style="fill:rgb(27,54,100);fill-rule:nonzero;"/>
                        </g>
                        <g transform="matrix(1,0,0,1,157.101,42.9887)">
                            <path d="M0,5.033L0,4.282C-0.489,4.131 -1.334,3.981 -2.198,3.981C-3.606,3.981 -5.033,4.376 -5.033,5.747C-5.033,6.873 -4.245,7.456 -2.986,7.456C-1.709,7.456 0,6.723 0,5.033M-10.066,-5.221C-8.94,-5.859 -6.104,-6.873 -2.649,-6.873C2.573,-6.873 6.103,-4.808 6.103,0.094L6.103,11.531L0.394,11.531L0.206,9.296C-0.77,10.685 -2.968,11.906 -5.352,11.906C-8.508,11.906 -11.005,9.916 -11.005,6.554C-11.005,2.554 -7.55,0.939 -2.93,0.939C-1.521,0.939 -0.545,1.126 0.188,1.314L0.188,0.864C0.188,-1.221 -1.146,-1.766 -3.681,-1.766C-5.934,-1.766 -8.376,-0.995 -9.503,-0.376L-10.066,-5.221Z" style="fill:rgb(27,54,100);fill-rule:nonzero;"/>
                        </g>
                        <g transform="matrix(1,0,0,1,190.239,46.1432)">
                            <path d="M0,-1.652C0,-3.662 -1.464,-4.619 -2.948,-4.619C-4.432,-4.619 -5.99,-3.662 -5.99,-1.652L-5.99,8.376L-11.906,8.376L-11.906,-1.652C-11.906,-3.662 -13.427,-4.619 -14.855,-4.619C-16.338,-4.619 -17.859,-3.662 -17.859,-1.652L-17.859,8.376L-23.775,8.376L-23.775,-9.652L-18.348,-9.652L-18.16,-7.756C-16.883,-9.371 -14.704,-10.028 -12.582,-10.028C-10.178,-10.028 -7.868,-9.014 -6.911,-7.023C-5.446,-9.089 -3.117,-10.028 -0.844,-10.028C2.629,-10.028 5.916,-7.737 5.916,-3.342L5.916,8.376L0,8.376L0,-1.652Z" style="fill:rgb(27,54,100);fill-rule:nonzero;"/>
                        </g>
                        <g transform="matrix(1,0,0,1,76.5878,88.9797)">
                            <path d="M0,-26.255C2.723,-26.255 5.502,-25.673 8.038,-24.527L7.643,-19.156C5.746,-20.095 3.305,-20.64 0.901,-20.64C-3.869,-20.64 -8.47,-18.555 -8.47,-13.184C-8.47,-8.414 -4.771,-5.428 0.225,-5.428C2.948,-5.428 5.483,-6.029 7.662,-7.4L8.507,-2.16C5.596,-0.713 2.591,0 -0.733,0C-8.395,0 -14.799,-5.109 -14.799,-13.109C-14.799,-21.842 -7.625,-26.255 0,-26.255" style="fill:rgb(27,54,100);fill-rule:nonzero;"/>
                        </g>
                        <g transform="matrix(1,0,0,1,0,6.39)">
                            <path d="M94.396,82.214L88.48,82.214L88.48,64.185L94.396,64.185L94.396,82.214ZM88.18,58.645C88.18,56.729 89.495,55.396 91.41,55.396C93.326,55.396 94.678,56.71 94.678,58.645C94.678,60.56 93.326,61.875 91.41,61.875C89.513,61.875 88.18,60.56 88.18,58.645" style="fill:rgb(27,54,100);fill-rule:nonzero;"/>
                        </g>
                        <g transform="matrix(1,0,0,1,106.252,78.3683)">
                            <path d="M0,-2.967L0,2.855C0,5.916 2.723,5.653 5.577,4.978L5.991,9.785C4.676,10.273 2.704,10.611 1.202,10.611C-2.93,10.611 -5.916,8.621 -5.916,3.813L-5.916,-2.967L-8.789,-2.967L-8.789,-7.793L-5.916,-7.793L-5.916,-13.578L-0.188,-13.578L-0.188,-7.793L4.901,-7.793L4.901,-2.967L0,-2.967Z" style="fill:rgb(27,54,100);fill-rule:nonzero;"/>
                        </g>
                        <g transform="matrix(1,0,0,1,113.737,96.4918)">
                            <path d="M0,-25.917L6.592,-25.917L10.405,-13.86L14.123,-25.917L20.733,-25.917L12.377,-5.239C10.93,-1.653 8.039,0 4.714,0C3.418,0 2.085,-0.32 0.827,-0.94L1.634,-5.597C2.348,-5.277 3.061,-5.108 3.737,-5.108C5.353,-5.108 6.724,-6.029 7.249,-7.869L0,-25.917Z" style="fill:rgb(27,54,100);fill-rule:nonzero;"/>
                        </g>
                        <g transform="matrix(1,0,0,1,80.4562,121.279)">
                            <path d="M0,-22.686L-0.507,-17.409C-1.427,-18.01 -5.277,-19.137 -8.319,-19.137C-10.498,-19.137 -12.263,-18.573 -12.263,-16.846C-12.263,-15.268 -10.761,-14.874 -9.296,-14.46L-5.653,-13.409C-0.958,-12.057 1.371,-10.122 1.371,-6.103C1.371,-1.427 -2.573,1.784 -8.789,1.784C-12.545,1.784 -16.752,0.733 -18.423,-0.206L-17.935,-5.634C-17.296,-4.939 -12.733,-3.625 -9.221,-3.625C-6.911,-3.625 -4.977,-4.188 -4.977,-5.897C-4.977,-7.08 -5.784,-7.681 -7.643,-8.169L-11.625,-9.183C-15.024,-10.292 -18.592,-11.963 -18.592,-16.545C-18.592,-21.296 -14.611,-24.47 -8.583,-24.47C-4.883,-24.47 -2.047,-23.7 0,-22.686" style="fill:rgb(27,54,100);fill-rule:nonzero;"/>
                        </g>
                        <g transform="matrix(1,0,0,1,94.3688,123.064)">
                            <path d="M0,-18.78C2.066,-18.78 3.719,-18.499 5.934,-17.578L5.503,-12.546C4.188,-13.09 2.573,-13.428 1.052,-13.428C-1.653,-13.428 -4.056,-12.433 -4.056,-9.39C-4.056,-6.78 -1.934,-5.428 0.733,-5.428C2.685,-5.428 3.887,-5.86 5.577,-6.667L6.159,-1.484C4.075,-0.489 2.441,0 0.075,0C-5.691,0 -9.973,-3.832 -9.973,-9.353C-9.973,-15.55 -5.578,-18.78 0,-18.78" style="fill:rgb(27,54,100);fill-rule:nonzero;"/>
                        </g>
                        <g transform="matrix(1,0,0,1,109.97,112.715)">
                            <path d="M0,-6.498C1.296,-7.869 3.324,-8.431 5.296,-8.431C8.696,-8.431 11.963,-6.047 11.963,-1.746L11.963,9.972L6.047,9.972L6.047,-0.056C6.047,-2.065 4.451,-3.023 3.024,-3.023C1.54,-3.023 0.019,-2.065 0.019,-0.056L0.019,9.972L-5.916,9.972L-5.916,-16.47L0,-16.47L0,-6.498Z" style="fill:rgb(27,54,100);fill-rule:nonzero;"/>
                        </g>
                        <g transform="matrix(1,0,0,1,130.76,113.673)">
                            <path d="M0,0C0,2.441 1.578,4.15 3.831,4.15C6.085,4.15 7.663,2.441 7.663,0C7.663,-2.441 6.085,-4.15 3.831,-4.15C1.578,-4.15 0,-2.441 0,0M13.522,0C13.522,5.371 9.409,9.39 3.831,9.39C-1.746,9.39 -5.859,5.371 -5.859,0C-5.859,-5.372 -1.746,-9.39 3.831,-9.39C9.409,-9.39 13.522,-5.372 13.522,0" style="fill:rgb(27,54,100);fill-rule:nonzero;"/>
                        </g>
                        <g transform="matrix(1,0,0,1,152.24,113.673)">
                            <path d="M0,0C0,2.441 1.578,4.15 3.831,4.15C6.085,4.15 7.663,2.441 7.663,0C7.663,-2.441 6.085,-4.15 3.831,-4.15C1.578,-4.15 0,-2.441 0,0M13.522,0C13.522,5.371 9.409,9.39 3.831,9.39C-1.746,9.39 -5.859,5.371 -5.859,0C-5.859,-5.372 -1.746,-9.39 3.831,-9.39C9.409,-9.39 13.522,-5.372 13.522,0" style="fill:rgb(27,54,100);fill-rule:nonzero;"/>
                        </g>
                        <g transform="matrix(-1,0,0,1,343.653,74.932)">
                            <rect x="168.869" y="21.313" width="5.915" height="26.442" style="fill:rgb(27,54,100);"/>
                        </g>
                        <g transform="matrix(1,0,0,1,186.036,104.283)">
                            <path d="M0,18.78C-2.667,18.78 -5.315,18.311 -7.043,17.54L-6.461,12.864C-4.733,13.54 -2.16,14.066 -0.507,14.066C1.465,14.066 2.216,13.784 2.216,13.014C2.216,12.339 1.671,12.057 -0.207,11.756L-1.879,11.493C-5.635,10.911 -7.324,9.202 -7.324,6.01C-7.324,2.347 -4.094,0 0.958,0C3.267,0 5.39,0.395 7.117,1.165L6.667,5.765C5.521,5.296 2.836,4.808 1.277,4.808C-0.733,4.808 -1.691,5.183 -1.691,5.972C-1.691,6.61 -0.958,6.949 1.089,7.268L2.836,7.549C6.31,8.113 7.925,9.803 7.925,12.883C7.925,16.489 4.864,18.78 0,18.78" style="fill:rgb(27,54,100);fill-rule:nonzero;"/>
                        </g>
                    </g>
                </g>
            </svg>
        </div>
        <div class="mt-5 flex-1 h-0 overflow-y-auto">
            <nav class="px-2 space-y-1">
                <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
                <a href="#" class="bg-gray-900 text-white group flex items-center px-2 py-2 text-base font-medium rounded-md">
                    <!--
                      Heroicon name: outline/home

                      Current: "text-gray-300", Default: "text-gray-400 group-hover:text-gray-300"
                    -->
                    <svg class="text-gray-300 mr-4 flex-shrink-0 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>

                <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-base font-medium rounded-md">
                    <!-- Heroicon name: outline/users -->
                    <svg class="text-gray-400 group-hover:text-gray-300 mr-4 flex-shrink-0 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Team
                </a>

                <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-base font-medium rounded-md">
                    <!-- Heroicon name: outline/folder -->
                    <svg class="text-gray-400 group-hover:text-gray-300 mr-4 flex-shrink-0 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                    </svg>
                    Projects
                </a>

                <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-base font-medium rounded-md">
                    <!-- Heroicon name: outline/calendar -->
                    <svg class="text-gray-400 group-hover:text-gray-300 mr-4 flex-shrink-0 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Calendar
                </a>

                <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-base font-medium rounded-md">
                    <!-- Heroicon name: outline/inbox -->
                    <svg class="text-gray-400 group-hover:text-gray-300 mr-4 flex-shrink-0 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    Documents
                </a>

                <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-base font-medium rounded-md">
                    <!-- Heroicon name: outline/chart-bar -->
                    <svg class="text-gray-400 group-hover:text-gray-300 mr-4 flex-shrink-0 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Reports
                </a>
            </nav>
        </div>
    </div>

    <div class="flex-shrink-0 w-14" aria-hidden="true">
        <!-- Dummy element to force sidebar to shrink to fit close icon -->
    </div>
</div>
