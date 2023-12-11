import { Component, EventEmitter, Input, Output } from '@angular/core';
import { bootstrapApplication } from '@angular/platform-browser';
import { HIGHLIGHT_OPTIONS, HighlightLoader, HighlightOptions } from 'ngx-highlightjs';
import { QuizQuestion } from 'src/app/entities/quiz-question.model';
import { QuizService } from 'src/app/services/quiz.service';

@Component({
  selector: 'code-snippet',
  templateUrl: './code-snippet.component.html',
  styleUrls: ['./code-snippet.component.scss']
})
export class CodeSnippetComponent {
  @Input() questions: any
  @Input() duration: number = 5;
  // @Input() languages: string[] = ['C#', 'Java', 'JavaScript', 'Python', 'TypeScript'];
  @Output() quizCompleted: EventEmitter<void> = new EventEmitter<void>();
  selectedAnswer: string | null = null;
  progress: number = 0;
  displayTime: string | undefined;
  timer: any | null = null;
code! : string;
  constructor(private quizService: QuizService) {
  }

  ngOnInit() {

    this.code = `def find_average(numbers):
      total_sum = sum(numbers)
      count = len(numbers)
      average = total_sum / count  # Potential error when count is zero
      return average
  numbers = []
  print(find_average(numbers))`;
    this.code = `  exports: [CodeSnippetComponent],
    providers: [
      {
        provide: HIGHLIGHT_OPTIONS,
        useValue: {
          coreLibraryLoader: () => import('highlight.js/lib/core'),
          lineNumbersLoader: () => import('ngx-highlightjs/line-numbers'), // Optional, only if you want the line numbers
          languages: {
            typescript: () => import('highlight.js/lib/languages/typescript'),
            css: () => import('highlight.js/lib/languages/css'),
            xml: () => import('highlight.js/lib/languages/xml'),
            python: () => import('highlight.js/lib/languages/python') // Add this line for Python
          },
        }
      }
    ]`;
    this.startTimer();
  }

  nextQuestion() {
    console.log('Next question');
    this.quizCompleted.emit();
  }

  onSelectedAnswer(answer: string) {
    this.selectedAnswer = answer;
  }

  private startTimer() {
    let time = 60 * this.duration;
    this.displayTime = this.formatTime(time);
    this.timer = setInterval(() => {
      time--;
      this.progress = time / 300;
      this.displayTime = this.formatTime(time);
      if (time <= 0) {
        clearInterval(this.timer);
      }
    }, 1000);
  }

  private formatTime(time: number): string {
    const minutes = Math.floor(time / 60);
    const seconds = time % 60;
    this.quizService.setTimeStatus(`${minutes}:${seconds < 10 ? '0' + seconds : seconds}`);
    return `Time left ${minutes}:${seconds < 10 ? '0' + seconds : seconds}`;
  }

  get currentQuestion(): QuizQuestion {
    return this.questions;
  }
}

