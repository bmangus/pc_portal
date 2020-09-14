<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExtendedCareRegistration extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ChildFullName'=>'required',
            'Gender'=>'required',
            'DateofBirth'=>'required|date',
            'School'=>'required',
            'Grade'=>'required',
            'Plan'=>'required',
            'Street'=>'required',
            'City'=>'required',
            'State'=>'required',
            'Zip'=>'required',
            'PrimaryContactFullName1'=>'required',
            'PrimaryContactType1'=>'required',
            'PC1Email'=>'required',
            'PC1Phone'=>'required',
            'PC1Employee'=>'required',
            'PC1WorkPhone'=>'required',
            'EC1'=>'required',
            'EC1_Phone'=>'required',
            'EC1_Relationship'=>'required',
            'EC1Checkbox'=>'required',
            'EC2'=>'required',
            'EC2_Phone'=>'required',
            'EC2_Relationship'=>'required',
            'EC2Checkbox'=>'required',
            'Authorized_Pickup1'=>'required',
            'Authorized_Pickup2'=>'required',
            'CustodyIssues'=>'nullable',
            'Medical_Conditions'=>'nullable',
            'Allergies'=>'nullable',
            'EC3'=>'nullable',
            'EC3_Phone'=>'nullable',
            'EC3_Relationship'=>'nullable',
            'EC3Checkbox'=>'nullable',
            'PrimaryContactFullName2'=>'nullable',
            'PrimaryContactType2'=>'nullable',
            'PC2Phone'=>'nullable',
            'PC2Email'=>'nullable',
            'PC2Employee'=>'nullable',
            'PC2WorkPhone'=>'nullable',
        ];
    }

    public function messages()
    {
        return [
            'ChildFullName.required'=>'Please enter your child\'s full name.',
            'Gender.required'=>'Please specify your child\'s gender.',
            'DateofBirth.required'=>'Please enter a valid Date of Birth.',
            'School.required'=>'Please specify your child\'s school.',
            'Grade.required'=>'Please specify your child\'s grade.',
            'Plan.required'=>'Please specify your enrollment preference.',
            'Street.required'=>'Please enter your street address.',
            'City.required'=>'Please enter your city.',
            'State.required'=>'Please specify your state.',
            'Zip.required'=>'Please enter your zip code.',
            'PrimaryContactFullName1.required'=>'Please enter this child\'s primary caregiver\'s full name.',
            'PrimaryContactType1.required'=>'Please tell us how this person is related to the child.',
            'PC1Email.required'=>'Please provide the first primary caregiver\'s email address.',
            'PC1Phone.required'=>'Please provide the first primary caregiver\'s phone number.',
            'PC1Employee.required'=>'Please provide the first primary caregiver\'s employer.',
            'PC1WorkPhone.required'=>'Please provide a work phone number for the first primary caregiver.',
            'EC1.required'=>'Please provide the first emergency contact.',
            'EC1_Phone.required'=>'Please provide the first emergency contact phone number.',
            'EC1_Relationship.required'=>'Please tell us how the first emergency contact is related to the child.',
            'EC1Checkbox.required'=>'Please tell us if emergency contact one is authorized to pickup this child.',
            'EC2.required'=>'Please provide a second emergency contact.',
            'EC2_Phone.required'=>'Please provide the second emergency contact phone number.',
            'EC2_Relationship.required'=>'Please tell us how the second emergency contact is related to the child.',
            'EC2Checkbox.required'=>'Please tell us if emergency contact two is authorized to pickup this child.',
            'Authorized_Pickup1.required'=>'Please tell us the first person authorized to pickup this child.',
            'Authorized_Pickup2.required'=>'Please tell us the second person authorized to pickup this child.'
        ];
    }
}
