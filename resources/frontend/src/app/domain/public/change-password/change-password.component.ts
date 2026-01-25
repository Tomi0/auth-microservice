import {Component, OnInit} from '@angular/core';
import {InputComponent} from "../../../application/shared/component/input/input.component";
import {ReactiveFormsModule} from "@angular/forms";
import {FormService} from "../../../application/shared/service/form.service";
import {ActivatedRoute} from "@angular/router";
import {ToastrService} from "ngx-toastr";
import {NgIf} from "@angular/common";
import {ChangePasswordService} from "./change-password.service";
import {ValidationService} from "../../../infrastructure/validation/validation.service";

@Component({
  selector: 'app-change-password',
  standalone: true,
  imports: [
    InputComponent,
    ReactiveFormsModule,
    NgIf
  ],
  templateUrl: './change-password.component.html',
  styleUrl: './change-password.component.scss'
})
export class ChangePasswordComponent {
  protected passwordChanged: boolean;

  constructor(protected formService: FormService,
              private toastrService: ToastrService,
              private changePasswordSevice: ChangePasswordService,
              private validationService: ValidationService,
              private activatedRoute: ActivatedRoute) {

    this.passwordChanged = false;
    this.formService.buildForm({
      token: this.activatedRoute.snapshot.queryParamMap.get('token'),
      email: this.activatedRoute.snapshot.queryParamMap.get('email'),
      password: null
    })
  }

  protected sendChangePasswordRequest(): void {
    this.changePasswordSevice.__invoke(this.formService.formData()).subscribe({
      next: () => {
        this.toastrService.success('Password changed');
        this.passwordChanged = true;
      },
      error: (errorResponse) => {
        this.toastrService.error('Oops! Something went wrong.');
        if (errorResponse.status === 422) {
          this.validationService.validateForm(this.formService.getForm(), errorResponse.error.errors);
        }
      }
    });
  }
}
