import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {FullScreenLayoutComponent} from "./infrastructure/full-screen-layout/full-screen-layout.component";

const routes: Routes = [
  {
    path: 'auth',
    component: FullScreenLayoutComponent,
    children: [
      {
        path: 'login',
        loadChildren: () => import('./domain/login/login.module').then(m => m.LoginModule),
      },
      {
        path: 'signup',
        loadChildren: () => import('./domain/signup/signup.module').then(m => m.SignupModule),
      },
    ]
  },

];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule {
}
