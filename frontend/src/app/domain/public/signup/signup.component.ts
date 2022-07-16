import { Component, OnInit } from '@angular/core';
import {FormBuilder, FormGroup} from "@angular/forms";
import {SignupService} from "./signup.service";
import {ToastrService} from "ngx-toastr";
import {FormValidator} from "../../../infrastructure/validation/form-validation.service";
import {Router} from "@angular/router";

@Component({
  selector: 'app-signup',
  templateUrl: './signup.component.html',
  styleUrls: ['./signup.component.scss']
})
export class SignupComponent implements OnInit {
  public error: boolean = false;
  public signupForm: FormGroup = {} as FormGroup;
  public creatingUser: boolean = false;

  constructor(private formBuilder: FormBuilder,
              private signupService: SignupService,
              private toastrService: ToastrService,
              private formValidator: FormValidator,
              private router: Router) {
    this.buildForm();
  }

  ngOnInit(): void {
  }

  private buildForm(): void {
    this.signupForm = this.formBuilder.group({
      email: null,
      password: null,
    });
    this.signupForm.markAllAsTouched();
  }

  public signup(): void {
    this.creatingUser = true;
    this.signupService.__invoke(this.signupForm.value).subscribe({
      next: () => {
        this.creatingUser = false;
        this.toastrService.success('User registered.');
        this.router.navigate(['/auth/login']).then();
      },
      error: (errorResponse) => {
        this.creatingUser = false;
        if (errorResponse.status === 422) {
          this.formValidator.validateForm(this.signupForm, errorResponse.error.errors);
          return;
        }
        this.toastrService.error('Unable to register user.')
      }
    })
  }
}
