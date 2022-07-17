import {Component, OnInit} from '@angular/core';
import {SearchUsersService} from "./search-users.service";
import {FormBuilder, FormGroup} from "@angular/forms";
import {User} from "./user.model";
import {ToastrService} from "ngx-toastr";
import {debounceTime} from "rxjs";

@Component({
  selector: 'app-users',
  templateUrl: './users.component.html',
  styleUrls: ['./users.component.scss']
})
export class UsersComponent implements OnInit {
  public searchUsersForm: FormGroup = {} as FormGroup;
  public users: User[] = [];

  constructor(private formBuilder: FormBuilder,
              private searchUsersService: SearchUsersService,
              private toastrService: ToastrService) {
    this.buildForm();
  }

  ngOnInit(): void {
    this.searchUsers();
  }

  private buildForm() {
    this.searchUsersForm = this.formBuilder.group({
      email: null,
      admin: null,
    });

    this.searchUsersForm.valueChanges.pipe(debounceTime(500)).subscribe(() => {
      this.searchUsers();
    })
  }

  private searchUsers(): void {
    this.searchUsersService.__invoke(this.searchUsersForm.value).subscribe({
      next: (users: User[]) => {
        this.users = users;
      },
      error: () => {
        this.toastrService.error('Failed to search users.');
      }
    })
  }
}
