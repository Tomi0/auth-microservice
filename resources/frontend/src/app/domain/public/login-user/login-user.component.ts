import {Component} from '@angular/core';
import {CommonModule} from "@angular/common";
import {ActivatedRoute, Router} from "@angular/router";
import {InputComponent} from "../../../application/shared/component/input/input.component";
import {ReactiveFormsModule} from "@angular/forms";
import {FormService} from "../../../application/shared/service/form.service";
import {LoginService} from "./login.service";
import {ValidationService} from "../../../infrastructure/validation/validation.service";
import {ToastrService} from "ngx-toastr";
import {AuthorizationCode} from "../../../application/shared/model/AuthorizationCode.model";

@Component({
  selector: 'app-login-user',
  standalone: true,
  imports: [CommonModule, InputComponent, ReactiveFormsModule],
  templateUrl: './login-user.component.html',
  styleUrl: './login-user.component.scss'
})
export class LoginUserComponent {

  private redirectUrl: null|string;
  private clientName: string|null;

  public constructor(public formService: FormService,
                     protected validationService: ValidationService,
                     protected router: Router,
                     protected toastrService: ToastrService,
                     protected activatedRoute: ActivatedRoute,
                     protected loginService: LoginService) {
    this.redirectUrl = this.activatedRoute.snapshot.queryParamMap.get('redirectUrl');
    this.clientName = this.activatedRoute.snapshot.queryParamMap.get('clientName');

    this.formService.buildForm({
      email: null,
      password: null,
      redirectUrl: this.redirectUrl,
      clientName: this.clientName,
    });
  }

  public redirectToRegister(): void {
    const queryParams = { ...this.activatedRoute.snapshot.queryParams };
    this.router.navigate(['/auth/register'], { queryParams });
  }

  public sendLoginRequest(): void {
    this.loginService.__invoke(this.formService.formData()).subscribe({
      next: (token: AuthorizationCode) => {
        this.toastrService.success('Log in successfully');
        setTimeout(() => {
          window.location.href = `${this.redirectUrl}?code=${encodeURIComponent(token.code)}`;
        }, 250);
      },
      error: (errorResponse) => {
        if (errorResponse.status === 422) {
          this.validationService.validateForm(this.formService.getForm(), errorResponse.error.errors);
        } else if (errorResponse.status === 401) {
          this.toastrService.error('Invalid credentials.');
        } else {
          this.toastrService.error('An error occurred while trying to log in. Please try again later.')
        }
      }
    })
  }
}
