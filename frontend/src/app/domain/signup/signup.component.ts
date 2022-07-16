import { Component, OnInit } from '@angular/core';
import {FormBuilder, FormGroup} from "@angular/forms";

@Component({
  selector: 'app-signup',
  templateUrl: './signup.component.html',
  styleUrls: ['./signup.component.scss']
})
export class SignupComponent implements OnInit {
  public error: boolean = false;
  public signupForm: FormGroup = {} as FormGroup;

  constructor(private formBuilder: FormBuilder) {
    this.buildForm();
  }

  ngOnInit(): void {
  }

  private buildForm(): void {
    this.signupForm = this.formBuilder.group({
      email: null,
      password: null,
    })
  }

}
