import { Component, OnInit } from '@angular/core';
import {FormBuilder, FormGroup} from "@angular/forms";

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {
  public error: boolean = false;
  public loginForm: FormGroup = {} as FormGroup;

  constructor(private formBuilder: FormBuilder) {
    this.buildForm();
  }

  ngOnInit(): void {
  }

  private buildForm(): void {
    this.loginForm = this.formBuilder.group({
      email: null,
      password: null,
    })
  }
}
