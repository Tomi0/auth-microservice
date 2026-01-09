import { Component } from '@angular/core';
import {InputComponent} from "../../../application/shared/component/input/input.component";
import {ReactiveFormsModule} from "@angular/forms";
import {FormService} from "../../../application/shared/service/form.service";
import {ActivatedRoute, Router} from "@angular/router";
import {SendTokenResetPasswordService} from "./send-token-reset-password.service";
import {ToastrService} from "ngx-toastr";
import {ValidationService} from "../../../infrastructure/validation/validation.service";

@Component({
  selector: 'app-reset-password',
  standalone: true,
  imports: [
    InputComponent,
    ReactiveFormsModule
  ],
  templateUrl: './reset-password.component.html',
  styleUrl: './reset-password.component.scss'
})
export class ResetPasswordComponent {

  public constructor(public formService: FormService,
                     protected router: Router,
                     protected toastrService: ToastrService,
                     protected sendTokenResetPasswordService: SendTokenResetPasswordService,
                     protected validationService: ValidationService,
                     protected activatedRoute: ActivatedRoute) {
    this.formService.buildForm({
      email: null,
    });
  }

  protected sendResetLinkRequest(): void {
    this.sendTokenResetPasswordService.__invoke(this.formService.formData()).subscribe({
      next: () => {
        this.toastrService.success('Email sent. If this email is registered, please check your inbox.')
      },
      error: (errorResponse) => {
        if (422 === errorResponse.status) {
          this.validationService.validateForm(this.formService.getForm(), errorResponse.error.errors);
        } else {
          this.toastrService.error('Something went wrong.')
        }
      }
    });
  }

  protected redirectToLogin(): void {
    this.redirect('/auth/login');
  }

  private redirect(url: string): void {
    const queryParams = {...this.activatedRoute.snapshot.queryParams};
    this.router.navigate([url], {queryParams});
  }

}
