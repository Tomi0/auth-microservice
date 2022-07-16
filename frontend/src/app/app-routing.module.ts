import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {FullScreenLayoutComponent} from "./infrastructure/full-screen-layout/full-screen-layout.component";
import {MenuLayoutComponent} from "./infrastructure/menu-layout/menu-layout.component";

const routes: Routes = [
  {
    path: 'auth',
    component: FullScreenLayoutComponent,
    children: [
      {
        path: 'login',
        loadChildren: () => import('./domain/public/login/login.module').then(m => m.LoginModule),
      },
      {
        path: 'signup',
        loadChildren: () => import('./domain/public/signup/signup.module').then(m => m.SignupModule),
      },
    ]
  },
  {
    path: 'backoffice-admin',
    component: MenuLayoutComponent,
    children: [
      {
        path: 'users',
        loadChildren: () => import('./domain/admin/users/users.module').then(m => m.UsersModule),
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
