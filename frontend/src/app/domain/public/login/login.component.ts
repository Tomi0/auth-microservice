import { Component, OnInit } from '@angular/core';
import {FormBuilder, FormGroup} from "@angular/forms";
import {LoginService} from "./login.service";
import {FormValidator} from "../../../infrastructure/validation/form-validation.service";
import {Router} from "@angular/router";

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {
  public error: boolean = false;
  public loginForm: FormGroup = {} as FormGroup;
  public loggingIn: boolean = false;

  constructor(private formBuilder: FormBuilder,
              private loginService: LoginService,
              private formValidator: FormValidator,
              private router: Router) {
    this.buildForm();
  }

  ngOnInit(): void {
  }

  private buildForm(): void {
    this.loginForm = this.formBuilder.group({
      email: null,
      password: null,
    });
    this.loginForm.markAllAsTouched();
  }

  public login(): void {
    this.loggingIn = true;
    this.loginService.__invoke(this.loginForm.value).subscribe({
      next: (token: string) => {
        localStorage.setItem('jwt-token', token);
        this.loggingIn = false;
        this.router.navigate(['backoffice-admin/users']).then();
      },
      error: (errorResponse) => {
        this.loggingIn = false;
        if (errorResponse.status === 422) {
          this.formValidator.validateForm(this.loginForm, errorResponse.error.errors);
          return;
        }

        this.error = true;
      }
    })
  }
}
