import {Injectable} from '@angular/core';
import {AbstractControl, FormArray, FormGroup} from "@angular/forms";

@Injectable({
  providedIn: 'root'
})
export class ValidationService {

  constructor() {
  }

  public validateForm(formGroup: FormGroup, validationMessages: any): void {
    for (const validationMessage in validationMessages) {
      const splitedMessage = validationMessage.split('.');
      if (splitedMessage.length > 1) {
        const validationControlFormArray = this.getValidationControlFromArray(formGroup, validationMessage);
        validationControlFormArray.setErrors(validationMessages[validationMessage]);
      } else {
        formGroup.controls[validationMessage].setErrors(validationMessages[validationMessage]);
      }
    }
  }

  private getValidationControlFromArray(formGroup: FormGroup, validationMessage: string): AbstractControl {
    const splitedMessage = validationMessage.split('.');
    const abstractControl = formGroup.get(splitedMessage[0]) as FormArray;
    if (abstractControl.at(Number(splitedMessage[1])).get(splitedMessage[2]) instanceof FormArray) {
      return (abstractControl.at(Number(splitedMessage[1])).get(splitedMessage[2]) as FormArray).at(Number(splitedMessage[3])).get(splitedMessage[4]) as FormGroup;
    }
    return (abstractControl.at(Number(splitedMessage[1]))).get(splitedMessage[2]) as FormArray;
  }
}
