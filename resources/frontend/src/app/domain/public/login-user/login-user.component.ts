import { Component } from '@angular/core';
import {CommonModule} from "@angular/common";
import {RouterLink} from "@angular/router";
import {InputComponent} from "../../../application/shared/component/input/input.component";
import {ReactiveFormsModule} from "@angular/forms";
import {FormService} from "../../../application/shared/service/form.service";
import {LoginService} from "./login.service";
import {ValidationService} from "../../../infrastructure/validation/validation.service";
import {ToastrService} from "ngx-toastr";

@Component({
  selector: 'app-login-user',
  standalone: true,
  imports: [CommonModule, RouterLink, InputComponent, ReactiveFormsModule],
  templateUrl: './login-user.component.html',
  styleUrl: './login-user.component.scss'
})
export class LoginUserComponent {

  public constructor(public formService: FormService,
                     protected validationService: ValidationService,
                     protected toastrService: ToastrService,
                     protected loginService: LoginService) {
    this.formService.buildForm({
      email: null,
      password: null,
    });
  }


  public sendLoginRequest(): void {
    this.loginService.__invoke(this.formService.formData()).subscribe({
      next: (token: string) => {
        localStorage.setItem('token', token);
        this.toastrService.success('Log in successfully')
      },
      error: (errorResponse) => {
        if (errorResponse.status === 422) {
          this.validationService.validateForm(this.formService.getForm(), errorResponse.error.errors);
        } else {
          this.toastrService.error('An error occurred while trying to log in. Please try again later.')
        }
      }
    })
  }
}
