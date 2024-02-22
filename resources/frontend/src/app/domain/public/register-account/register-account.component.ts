import {Component} from '@angular/core';
import {CommonModule} from "@angular/common";
import {Router, RouterLink} from "@angular/router";
import {FormBuilder, FormControl, FormGroup, ReactiveFormsModule} from "@angular/forms";
import {RegisterUserService} from "./register-user.service";
import {User} from "../../../application/shared/model/User.model";
import {ValidationService} from "../../../infrastructure/validation/validation.service";
import {InputComponent} from "../../../application/shared/components/input/input.component";

@Component({
  selector: 'app-register-account',
  standalone: true,
  imports: [CommonModule, RouterLink, ReactiveFormsModule, InputComponent],
  templateUrl: './register-account.component.html',
  styleUrl: './register-account.component.scss'
})
export class RegisterAccountComponent {

  public crearContactoForm: FormGroup = {} as FormGroup;


  constructor(protected formBuilder: FormBuilder,
              protected router: Router,
              protected validationService: ValidationService,
              protected registerUserService: RegisterUserService) {
    this.buildForm();
  }

  public buildForm(): void {
    this.crearContactoForm = this.formBuilder.group({
      full_name: null,
      email: null,
      password: null,
    })
  }

  public sendCreateUserRequest(): void {
    this.registerUserService.__invoke(this.crearContactoForm.value).subscribe({
      next: (user: User) => {
        this.router.navigate(['/auth/login']);
      },
      error: (errorResponse) => {
        this.validationService.validateForm(this.crearContactoForm, errorResponse.error.errors)
      }
    })
  }

  public getFormControl(formControlName: string) {
    return this.crearContactoForm.get(formControlName) as FormControl;
  }
}
