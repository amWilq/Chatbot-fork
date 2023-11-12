interface IAssessment {
  assessmentTypeId?: string,
  name?: string,
  difficulties?: string[],
  format?: string,
}

export class Assessment implements IAssessment {
  constructor(
    public assessmentTypeId?: string,
    public name?: string,
    public difficulties?: string[],
    public format?: string,
  ) {}
}
