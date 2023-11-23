import { Component, Input } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { QuizService } from 'src/app/services/quiz.service';

@Component({
  selector: 'app-tabs',
  templateUrl: 'tabs.page.html',
  styleUrls: ['tabs.page.scss']
})
export class TabsPage {
  @Input() passedVariable: boolean = false;
  questions: any;
  selectedCategory: any;
  timer: string = '';

  constructor(private route: ActivatedRoute,private quizService: QuizService) {
    this.route.queryParams.subscribe(params => {
      this.passedVariable = params['questions'];
      this.questions = params['questions'];
      this.selectedCategory= params['selectedCategory'];
    });
  }

  ngOnInit(): void {
    this.quizService.getTimeStatus().subscribe((e) => {
      this.timer = e
    });;
  }
}
