import { Injectable } from '@angular/core';
import {FormBuilder, FormControl, FormGroup} from "@angular/forms";

@Injectable({
  providedIn: 'root'
})
export class FormService {

  private form: FormGroup = {} as FormGroup;

  constructor(protected formBuilder: FormBuilder) { }

  public buildForm(form: any) {
    this.form = this.formBuilder.group(form);
  }

  public formData(): any {
    return this.form?.value
  }

  public getForm(): any {
    return this.form
  }

  public getFormControl(formControlName: string) {
    return this.form.get(formControlName) as FormControl
  }
}
