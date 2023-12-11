import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { QuizService } from './services/quiz.service';
import { v4 as uuidv4 } from 'uuid';

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
    this.username = localStorage.getItem('userId');
  }

  onInput(ev: any) {
    this.usernameInput = ev.target!.value;
  }


  saveUsername() {
    if (this.isValidUsername()) {
      const userId = uuidv4();
      localStorage.setItem('userId', userId);
      this.username = userId;
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
