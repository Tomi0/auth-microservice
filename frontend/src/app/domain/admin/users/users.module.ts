import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {UsersComponent} from './users.component';
import {MatButtonModule} from "@angular/material/button";
import {RouterModule} from "@angular/router";
import {ReactiveFormsModule} from "@angular/forms";
import {MatExpansionModule} from "@angular/material/expansion";
import {MatFormFieldModule} from "@angular/material/form-field";
import {MatInputModule} from "@angular/material/input";
import {MatSelectModule} from "@angular/material/select";
import {MatTableModule} from "@angular/material/table";


@NgModule({
  declarations: [
    UsersComponent
  ],
  imports: [
    CommonModule,
    MatButtonModule,
    ReactiveFormsModule,
    RouterModule.forChild([
      {
        path: '',
        component: UsersComponent
      },
    ]),
    MatExpansionModule,
    MatFormFieldModule,
    MatInputModule,
    MatSelectModule,
    MatTableModule,
  ]
})
export class UsersModule {
}
