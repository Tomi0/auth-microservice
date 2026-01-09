import {Routes} from '@angular/router';
import {AuthLayoutComponent} from "./infrastructure/layout/auth-layout/auth-layout.component";
import {LoginUserComponent} from "./domain/public/login-user/login-user.component";
import {RegisterAccountComponent} from "./domain/public/register-account/register-account.component";
import {TestRedirectComponent} from "./domain/public/test-redirect/test-redirect.component";
import {ResetPasswordComponent} from "./domain/public/reset-password/reset-password.component";

export const routes: Routes = [
  {
    path: '',
    component: AuthLayoutComponent,
    children: [
      {
        path: '',
        redirectTo: '/auth/login',
        pathMatch: 'full'
      },
      {
        path: 'auth/login',
        component: LoginUserComponent
      },
      {
        path: 'auth/register',
        component: RegisterAccountComponent
      },
      {
        path: 'auth/reset-password',
        component: ResetPasswordComponent
      },
      {
        path: 'test-redirect',
        component: TestRedirectComponent
      },
    ]
  },
];
