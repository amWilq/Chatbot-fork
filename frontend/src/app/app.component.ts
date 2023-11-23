import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { QuizService } from './services/quiz.service';

@Component({
  selector: 'app-root',
  templateUrl: 'app.component.html',
  styleUrls: ['app.component.scss'],
})
export class AppComponent {
  username: string | null = null;
  usernameInput: string = '';

  constructor(private router: Router, private quizService: QuizService) {}

  ngOnInit() {
    document.body.classList.toggle('dark', true);
    this.loadUsername();
    this.loadQuizStatus();
  }

  private loadUsername() {
    this.username = localStorage.getItem('username');
    console.log('Wczytuję pseudonim:', this.username);
  }

  onInput(ev: any) {
    this.usernameInput = ev.target!.value;
  }

  saveUsername() {
    if (this.isValidUsername()) {
      localStorage.setItem('username', this.usernameInput);
      this.username = this.usernameInput;
    } else {
      console.log('Nieprawidłowy pseudonim!');
    }
  }

  private loadQuizStatus() {
    this.quizService.getQuizsStatus().subscribe((e) => {
    });
  }

  isValidUsername(): boolean {
    return this.usernameInput.trim() !== '';
  }
}
