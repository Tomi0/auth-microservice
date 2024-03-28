import {Component} from '@angular/core';
import {CommonModule} from "@angular/common";
import {Router, RouterLink} from "@angular/router";
import {FormBuilder, FormControl, FormGroup, ReactiveFormsModule} from "@angular/forms";
import {RegisterUserService} from "./register-user.service";
import {User} from "../../../application/shared/model/User.model";
import {ValidationService} from "../../../infrastructure/validation/validation.service";
import {InputComponent} from "../../../application/shared/component/input/input.component";
import {ToastrService} from "ngx-toastr";
import {FormService} from "../../../application/shared/service/form.service";

@Component({
  selector: 'app-register-account',
  standalone: true,
  imports: [CommonModule, RouterLink, ReactiveFormsModule, InputComponent],
  templateUrl: './register-account.component.html',
  styleUrl: './register-account.component.scss'
})
export class RegisterAccountComponent {

  constructor(public formService: FormService,
              protected router: Router,
              protected toastrService: ToastrService,
              protected validationService: ValidationService,
              protected registerUserService: RegisterUserService) {
    this.formService.buildForm({
      full_name: null,
      email: null,
      password: null,
    });
  }

  public sendCreateUserRequest(): void {
    this.registerUserService.__invoke(this.formService.formData()).subscribe({
      next: (user: User) => {
        this.toastrService.success('New account has been created')
        this.router.navigate(['/auth/login']);
      },
      error: (errorResponse) => {
        if (errorResponse.status === 422) {
          this.validationService.validateForm(this.formService.getForm(), errorResponse.error.errors)
        } else {
          this.toastrService.error('An error occurred while trying to create an account. Please try again later.')
        }
      }
    })
  }
}
