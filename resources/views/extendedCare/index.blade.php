<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <Title>PC Portal - Extended Care</Title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:200,600" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body class="bg-blue-100">
    <div class="mx-auto">
        <div class="flex-col max-w-5xl rounded flex p-8 mx-auto bg-white align-content-center content-between shadow-md">
            <img class="mx-auto md:content-start md:ml-1" src="{{asset('img/pc_logo.png')}}" alt="PC Logo"/>
            <div class="mt-2 ml-3">
                <p class="text-center md:text-left text-3xl mx-auto p-2">Putnam City Schools</p>
                <p class="text-center md:text-left text-2xl mx-auto p-2 text-gray-600">Extended Care Registration Form</p>
            </div>
        </div>
        <div class="max-w-5xl rounded overflow-hidden shadow-md bg-white mx-auto p-8 px-6 mt-8">
            <p class="text-secondary mb-4 float-right">Fields marked with an asterisk (*) are REQUIRED.</p>

            <form class="w-full" method="POST" action="/ExtendedCareRegistration">
                @csrf
                <p class="text-primary">Student and Enrollment Information</p>
                <hr/>
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="ChildFullName">
                            First and Last Name *
                        </label>
                        <input name="ChildFullName" class="block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4leading-tight focus:outline-none focus:bg-white" id="grid-first-name" type="text" placeholder="Full Name" value="{{old('ChildFullName')}}">
                        @if($errors->has('ChildFullName'))
                            <p class="text-red-500 text-xs italic">{{$errors->first('ChildFullName')}}</p>
                        @endif
                    </div>
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="PreferredName">
                            Student's Preferred Name
                        </label>
                        <input name="PreferredName" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-password" type="text" placeholder="Preferred Name" value="{{old('PreferredName')}}">
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="Gender">
                            Gender *
                        </label>
                        <div class="relative">
                            <select name="Gender" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray500" id="Gender">
                                <option class="text-gray-700" disabled selected value> -- select an option -- </option>
                                <option>Male</option>
                                <option>Female</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                        @if($errors->has('Gender'))
                            <p class="text-red-500 text-xs italic">{{$errors->first('Gender')}}</p>
                        @endif
                    </div>
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="DateofBirth">
                            Date Of Birth *
                        </label>
                        <input name="DateofBirth" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-zip" type="date" value="{{old('DateofBirth')}}">
                        @if($errors->has('DateofBirth'))
                            <p class="text-red-500 text-xs italic">{{$errors->first('DateofBirth')}}</p>
                        @endif
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-1/3 px-3 mb-6">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="School">
                            School/Site *
                        </label>
                        <div class="relative">
                            <select name="School" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-grey-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="School" >
                                <option class="text-gray-700" disabled selected value> -- select an option -- </option>
                                @foreach($sites as $site)
                                    <option>{{$site['SiteName']}}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                        @if($errors->has('School'))
                            <p class="text-red-500 text-xs italic">{{$errors->first('School')}}</p>
                        @endif
                    </div>
                    <div class="w-full md:w-1/3 px-3 mb-6">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="Grade">
                            Grade *
                        </label>
                        <div class="relative">
                            <select name="Grade" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-state" >
                                <option class="text-gray-700" disabled selected value> -- select an option -- </option>
                                <option>Pre-K</option>
                                <option>K</option>
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                        @if($errors->has('Grade'))
                            <p class="text-red-500 text-xs italic">{{$errors->first('Grade')}}</p>
                        @endif
                    </div>
                    <div class="w-full md:w-1/3 px-3 mb-6">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="Plan">
                            Enrollment *
                        </label>
                        <div class="relative">
                            <select name="Plan" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-state" >
                                <option class="text-gray-700" disabled selected value> -- select an option -- </option>
                                <option>AM Only</option>
                                <option>PM Only</option>
                                <option>AM & PM</option>
                                <option>AM Drop-In</option>
                                <option>PM Drop-In</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                        @if($errors->has('Plan'))
                            <p class="text-red-500 text-xs italic">{{$errors->first('Plan')}}</p>
                        @endif
                    </div>
                    <div class="w-full px-3">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="Allergies">
                            Allergies
                        </label>
                        <textarea name="Allergies" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 mb-2 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                            {{old('Allergies')}}
                        </textarea>
                    </div>
                    <div class="w-full px-3">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="Medical_Conditions">
                            Medical Conditions
                        </label>
                        <textarea name="Medical_Conditions" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 mb-2 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                            {{old('Medical_Conditions')}}
                        </textarea>
                    </div>
                </div>
                <p class="text-primary mt-4">Home Address</p>
                <hr/>
                <div class="flex flex-wrap -mx-3 mb-2">
                    <div class="w-full px-3">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="Street">
                            Street Address *
                        </label>
                        <input name="Street" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-zip" type="text" value="{{old('Street')}}">
                        @if($errors->has('Street'))
                            <p class="text-red-500 text-xs italic">{{$errors->first('Street')}}</p>
                        @endif
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-2">
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="City">
                            City *
                        </label>
                       <input name="City" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-zip" type="text" value="{{old('City')}}">
                        @if($errors->has('City'))
                            <p class="text-red-500 text-xs italic">{{$errors->first('City')}}</p>
                        @endif
                    </div>
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="State">
                            State *
                        </label>
                        <div class="relative">
                            <select name="State" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-state" >
                                <option value="AL">Alabama</option>
                                <option value="AK">Alaska</option>
                                <option value="AZ">Arizona</option>
                                <option value="AR">Arkansas</option>
                                <option value="CA">California</option>
                                <option value="CO">Colorado</option>
                                <option value="CT">Connecticut</option>
                                <option value="DE">Delaware</option>
                                <option value="DC">District Of Columbia</option>
                                <option value="FL">Florida</option>
                                <option value="GA">Georgia</option>
                                <option value="HI">Hawaii</option>
                                <option value="ID">Idaho</option>
                                <option value="IL">Illinois</option>
                                <option value="IN">Indiana</option>
                                <option value="IA">Iowa</option>
                                <option value="KS">Kansas</option>
                                <option value="KY">Kentucky</option>
                                <option value="LA">Louisiana</option>
                                <option value="ME">Maine</option>
                                <option value="MD">Maryland</option>
                                <option value="MA">Massachusetts</option>
                                <option value="MI">Michigan</option>
                                <option value="MN">Minnesota</option>
                                <option value="MS">Mississippi</option>
                                <option value="MO">Missouri</option>
                                <option value="MT">Montana</option>
                                <option value="NE">Nebraska</option>
                                <option value="NV">Nevada</option>
                                <option value="NH">New Hampshire</option>
                                <option value="NJ">New Jersey</option>
                                <option value="NM">New Mexico</option>
                                <option value="NY">New York</option>
                                <option value="NC">North Carolina</option>
                                <option value="ND">North Dakota</option>
                                <option value="OH">Ohio</option>
                                <option value="OK" selected>Oklahoma</option>
                                <option value="OR">Oregon</option>
                                <option value="PA">Pennsylvania</option>
                                <option value="RI">Rhode Island</option>
                                <option value="SC">South Carolina</option>
                                <option value="SD">South Dakota</option>
                                <option value="TN">Tennessee</option>
                                <option value="TX">Texas</option>
                                <option value="UT">Utah</option>
                                <option value="VT">Vermont</option>
                                <option value="VA">Virginia</option>
                                <option value="WA">Washington</option>
                                <option value="WV">West Virginia</option>
                                <option value="WI">Wisconsin</option>
                                <option value="WY">Wyoming</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                        @if($errors->has('State'))
                            <p class="text-red-500 text-xs italic">{{$errors->first('State')}}</p>
                        @endif
                    </div>
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="Zip">
                            Zipcode *
                        </label>
                        <input name="Zip" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-zip" type="text" value="{{old('Zip')}}">
                        @if($errors->has('Zip'))
                            <p class="text-red-500 text-xs italic">{{$errors->first('Zip')}}</p>
                        @endif
                    </div>
                </div>
                <p class="text-primary mt-4">Primary Caregiver Information</p>
                <hr/>
                <div class="flex flex-wrap -mx-3 mb-2">
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="PrimaryContactFullName1">
                            First and Last Name *
                        </label>
                        <input name="PrimaryContactFullName1" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-zip" type="text" value="{{old('PrimaryContactFullName1')}}">
                        @if($errors->has('PrimaryContactFullName1'))
                            <p class="text-red-500 text-xs italic">{{$errors->first('PrimaryContactFullName1')}}</p>
                        @endif
                    </div>
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="PrimaryContactType1">
                            Relationship *
                        </label>
                        <input name="PrimaryContactType1" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-zip" type="text" value="{{old('PrimaryContactType1')}}">
                        @if($errors->has('PrimaryContactType1'))
                            <p class="text-red-500 text-xs italic">{{$errors->first('PrimaryContactType1')}}</p>
                        @endif
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-2">
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="PC1Email">
                            Email Address *
                        </label>
                        <input name="PC1Email" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-zip" type="email" value="{{old('PC1Email')}}">
                        @if($errors->has('PC1Email'))
                            <p class="text-red-500 text-xs italic">{{$errors->first('PC1Email')}}</p>
                        @endif
                    </div>
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="PC1Phone">
                            Phone Number *
                        </label>
                        <input name="PC1Phone" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-zip" type="tel" value="{{old('PC1Phone')}}">
                        @if($errors->has('PC1Phone'))
                            <p class="text-red-500 text-xs italic">{{$errors->first('PC1Phone')}}</p>
                        @endif
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-2">
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="PC1Employee">
                            Employer *
                        </label>
                        <input name="PC1Employee" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-zip" type="text" value="{{old('PC1Employee')}}">
                        @if($errors->has('PC1Employee'))
                            <p class="text-red-500 text-xs italic">{{$errors->first('PC1Employee')}}</p>
                        @endif
                    </div>
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="PC1WorkPhone">
                            Work Phone Number *
                        </label>
                        <input name="PC1WorkPhone" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-zip" type="tel" value="{{old('PC1WorkPhone')}}">
                        @if($errors->has('PC1WorkPhone'))
                            <p class="text-red-500 text-xs italic">{{$errors->first('PC1WorkPhone')}}</p>
                        @endif
                    </div>
                </div>
                <p class="text-primary mt-4">Secondary Caregiver Information</p>
                <hr/>
                <div class="flex flex-wrap -mx-3 mb-2">
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="PrimaryContactFullName2">
                            First and Last Name
                        </label>
                        <input name="PrimaryContactFullName2" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-zip" type="text" value="{{old('PrimaryContactFullName2')}}">
                    </div>
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="PrimaryContactType2">
                            Relationship
                        </label>
                        <input name="PrimaryContactType2" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-zip" type="text" value="{{old('PrimaryContactType2')}}">
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-2">
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="PC2Email">
                            Email Address
                        </label>
                        <input name="PC2Email" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-zip" type="email" value="{{old('PC2Email')}}">
                    </div>
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="PC2Phone">
                            Phone Number
                        </label>
                        <input name="PC2Phone" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-zip" type="tel" value="{{old('PC2Phone')}}">
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-2">
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="PC2Employee">
                            Employer
                        </label>
                        <input name="PC2Employee" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-zip" type="text" value="{{old('PC2Employee')}}">
                    </div>
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="PC2WorkPhone">
                            Work Phone Number
                        </label>
                        <input name="PC2WorkPhone" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-zip" type="tel" value="{{old('PC2WorkPhone')}}">
                    </div>
                </div>
                <p class="text-primary mt-4">Emergency Contacts</p>
                <hr/>
                <div class="flex flex-wrap -mx-3 mb-2">
                    <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="EC1">
                            First and Last Name *
                        </label>
                        <input name="EC1" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-zip" type="text" value="{{old('EC1')}}">
                        @if($errors->has('EC1'))
                            <p class="text-red-500 text-xs italic">{{$errors->first('EC1')}}</p>
                        @endif
                    </div>
                    <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="EC1_Phone">
                            Phone *
                        </label>
                        <input name="EC1_Phone" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-zip" type="tel" value="{{old('EC1_Phone')}}">
                        @if($errors->has('EC1_Phone'))
                            <p class="text-red-500 text-xs italic">{{$errors->first('EC1_Phone')}}</p>
                        @endif
                    </div>
                    <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="EC1_Relationship">
                            Relationship *
                        </label>
                        <input name="EC1_Relationship" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-zip" type="text" value="{{old('EC1_Relationship')}}">
                        @if($errors->has('EC1_Relationship'))
                            <p class="text-red-500 text-xs italic">{{$errors->first('EC1_Relationship')}}</p>
                        @endif
                    </div>
                    <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="School">
                            Authorized To Pickup? *
                        </label>
                        <div class="relative">
                            <select name="EC1Checkbox" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-grey-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="School" >
                                <option class="text-gray-700" disabled selected value> -- select an option -- </option>
                                <option value="Yes">Yes</option>
                                <option value="0">No</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                        @if($errors->has('EC1Checkbox'))
                            <p class="text-red-500 text-xs italic">{{$errors->first('EC1Checkbox')}}</p>
                        @endif
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-2">
                    <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="EC2">
                            First and Last Name *
                        </label>
                        <input name="EC2" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-zip" type="text" value="{{old('EC2')}}">
                        @if($errors->has('EC2'))
                            <p class="text-red-500 text-xs italic">{{$errors->first('EC2')}}</p>
                        @endif
                    </div>
                    <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="EC2_Phone">
                            Phone *
                        </label>
                        <input name="EC2_Phone" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-zip" type="tel" value="{{old('EC2_Phone')}}">
                        @if($errors->has('EC2_Phone'))
                            <p class="text-red-500 text-xs italic">{{$errors->first('EC2_Phone')}}</p>
                        @endif
                    </div>
                    <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="EC1_Relationship">
                            Relationship *
                        </label>
                        <input name="EC2_Relationship" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-zip" type="text" value="{{old('EC2_Relationship')}}">
                        @if($errors->has('EC2_Relationship'))
                            <p class="text-red-500 text-xs italic">{{$errors->first('EC2_Relationship')}}</p>
                        @endif
                    </div>
                    <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="School">
                            Authorized To Pickup? *
                        </label>
                        <div class="relative">
                            <select name="EC2Checkbox" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-grey-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="School" >
                                <option class="text-gray-700" disabled selected value> -- select an option -- </option>
                                <option value="Yes">Yes</option>
                                <option value="0">No</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                        @if($errors->has('EC2Checkbox'))
                            <p class="text-red-500 text-xs italic">{{$errors->first('EC2Checkbox')}}</p>
                        @endif
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-2">
                    <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="EC3">
                            First and Last Name
                        </label>
                        <input name="EC3" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-zip" type="text" value="{{old('EC3')}}">
                    </div>
                    <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="EC3_Phone">
                            Phone
                        </label>
                        <input name="EC3_Phone" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-zip" type="tel" value="{{old('EC3_Phone')}}">
                    </div>
                    <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="EC3_Relationship">
                            Relationship
                        </label>
                        <input name="EC3_Relationship" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-zip" type="text" value="{{old('EC3_Relationship')}}">
                    </div>
                    <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="School">
                            Authorized To Pickup?
                        </label>
                        <div class="relative">
                            <select name="EC3Checkbox" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-grey-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="School" >
                                <option class="text-gray-700" disabled selected value> -- select an option -- </option>
                                <option value="Yes">Yes</option>
                                <option value="0">No</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="text-primary mt-4">Pickup Information</p>
                <hr/>
                <div class="flex flex-wrap -mx-3 mb-2">
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="Authorized_Pickup1">
                            Authorized Pickup Full Name *
                        </label>
                        <input name="Authorized_Pickup1" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-zip" type="text" value="{{old('Authorized_Pickup1')}}">
                        @if($errors->has('Authorized_Pickup1'))
                            <p class="text-red-500 text-xs italic">{{$errors->first('Authorized_Pickup1')}}</p>
                        @endif
                    </div>
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="Authorized_Pickup2">
                            Authorized Pickup Full Name 2 *
                        </label>
                        <input name="Authorized_Pickup2" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-zip" type="text" value="{{old('Authorized_Pickup2')}}">
                        @if($errors->has('Authorized_Pickup2'))
                            <p class="text-red-500 text-xs italic">{{$errors->first('Authorized_Pickup2')}}</p>
                        @endif
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-2">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2 mx-3" for="CustodyIssues">
                        Custodial Issues?
                    </label>
                    <textarea name="CustodyIssues" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-2 px-4 pr-8 mx-3 mb-2 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                        {{old('CustodyIssues')}}
                        </textarea>
                </div>
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded float-right" type="submit" action="submit">Submit Application</button>
            </form>
        </div>
    </div>
    </body>
</html>
